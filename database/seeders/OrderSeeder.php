<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            $orderCount = random_int(1, 5);
            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::factory()
                    ->for($customer)
                    ->create();

                // Add 2-5 items per order
                $itemCount = random_int(2, 5);
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = random_int(1, 5);
                    $unitPrice = $product->price;
                    $subtotal = $unitPrice * $quantity;

                    OrderItem::factory()
                        ->for($order)
                        ->for($product)
                        ->create([
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'subtotal' => $subtotal,
                        ]);

                    // Update order total
                    $order->total_amount += $subtotal;
                }

                $order->save();
            }
        }
    }
}
