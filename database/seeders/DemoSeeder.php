<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('Password123!')
            ]
        );

        // Create products manually (NO factory)
        $products = collect([
            ['sku' => 'SKU-1001', 'name' => 'Pineapple Box', 'price_cents' => 1990],
            ['sku' => 'SKU-1002', 'name' => 'Organic Juice Pack', 'price_cents' => 2590],
            ['sku' => 'SKU-1003', 'name' => 'Farm Starter Kit', 'price_cents' => 4990],
            ['sku' => 'SKU-1004', 'name' => 'Fresh Produce Bundle', 'price_cents' => 3490],
            ['sku' => 'SKU-1005', 'name' => 'Wholesale Carton', 'price_cents' => 8990],
        ])->map(fn ($p) => Product::create($p));

        // Create demo orders
        foreach (range(1, 15) as $i) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'paid',
                'total_cents' => 0,
                'ordered_at' => now()->subDays(rand(0, 60)),
            ]);

            $total = 0;

            foreach ($products->random(rand(1, 3)) as $product) {
                $qty = rand(1, 3);
                $line = $product->price_cents * $qty;
                $total += $line;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_price_cents' => $product->price_cents,
                    'line_total_cents' => $line,
                ]);
            }

            $order->update(['total_cents' => $total]);
        }
    }
}
