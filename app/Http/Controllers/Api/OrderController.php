<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Optimized: eager loading avoids N+1
        return Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items.product'])
            ->orderByDesc('ordered_at')
            ->paginate(10);
    }

    public function store(StoreOrderRequest $request)
    {
        $userId = $request->user()->id;

        return DB::transaction(function () use ($request, $userId) {
            $items = $request->validated()['items'];

            // Pull all products in one query
            $productMap = Product::whereIn('id', collect($items)->pluck('product_id'))
                ->get()
                ->keyBy('id');

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'paid',
                'total_cents' => 0,
                'ordered_at' => now(),
            ]);

            $total = 0;

            foreach ($items as $i) {
                $p = $productMap[$i['product_id']];
                $line = $p->price_cents * (int)$i['qty'];
                $total += $line;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'qty' => (int)$i['qty'],
                    'unit_price_cents' => $p->price_cents,
                    'line_total_cents' => $line,
                ]);
            }

            $order->update(['total_cents' => $total]);

            Log::info('Order created', [
                'order_id' => $order->id,
                'user_id' => $userId,
                'total_cents' => $total
            ]);

            return response()->json(
                $order->load(['items.product']),
                201
            );
        });
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        return $order->load(['items.product']);
    }
}
