<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // /api/reports/sales?from=2025-01-01&to=2025-12-31
    public function sales(Request $request)
    {
        $data = $request->validate([
            'from' => ['required','date'],
            'to' => ['required','date','after_or_equal:from'],
        ]);

        $from = $data['from'];
        $to = $data['to'];

        // Optimized aggregation query (indexes support ordered_at, status)
        $summary = DB::table('orders')
            ->where('status', 'paid')
            ->whereBetween('ordered_at', [$from, $to])
            ->selectRaw('COUNT(*) as orders_count, SUM(total_cents) as total_cents')
            ->first();

        // Top products (joins w/ indexes on order_id/product_id)
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.status', 'paid')
            ->whereBetween('orders.ordered_at', [$from, $to])
            ->groupBy('products.id', 'products.sku', 'products.name')
            ->orderByRaw('SUM(order_items.qty) DESC')
            ->limit(5)
            ->get([
                'products.id',
                'products.sku',
                'products.name',
                DB::raw('SUM(order_items.qty) as qty_sold'),
                DB::raw('SUM(order_items.line_total_cents) as revenue_cents'),
            ]);

        return response()->json([
            'range' => compact('from', 'to'),
            'orders_count' => (int)($summary->orders_count ?? 0),
            'total_cents' => (int)($summary->total_cents ?? 0),
            'top_products' => $topProducts,
        ]);
    }
}
