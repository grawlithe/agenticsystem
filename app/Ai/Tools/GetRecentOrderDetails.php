<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetRecentOrderDetails implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Retrieve details about recent orders including customer information, items, and status.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $limit = (int) ($request['limit'] ?? 5);
        $status = $request['status'] ?? '';

        $query = Order::with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        if (! empty($status)) {
            $query->where('status', $status);
        }

        $orders = $query->limit($limit)->get();

        $result = [
            'recent_orders' => [],
        ];

        foreach ($orders as $order) {
            $items = $order->items->map(fn ($item) => [
                'product_sku' => $item->product->sku,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => round($item->unit_price, 2),
                'subtotal' => round($item->subtotal, 2),
            ]);

            $result['recent_orders'][] = [
                'order_number' => $order->order_number,
                'customer' => [
                    'name' => $order->customer->name,
                    'email' => $order->customer->email,
                    'city' => $order->customer->city,
                ],
                'total_amount' => round($order->total_amount, 2),
                'status' => $order->status,
                'item_count' => $order->items->count(),
                'items' => $items,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'shipped_at' => $order->shipped_at?->format('Y-m-d H:i:s'),
            ];
        }

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'limit' => $schema->integer()->default(5),
            'status' => $schema->string()->description('Optional status filter'),
        ];
    }
}
