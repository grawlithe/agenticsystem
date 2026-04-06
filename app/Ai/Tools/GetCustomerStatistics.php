<?php

namespace App\Ai\Tools;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCustomerStatistics implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Retrieve comprehensive customer statistics including total count, status breakdown, and average orders per customer.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $breakdown = $request['include_breakdown'] ?? true;

        $total = Customer::count();
        $activeCount = Customer::where('status', 'active')->count();
        $inactiveCount = Customer::where('status', 'inactive')->count();
        $suspendedCount = Customer::where('status', 'suspended')->count();

        $totalOrders = Order::count();
        $averageOrdersPerCustomer = $total > 0 ? round($totalOrders / $total, 2) : 0;

        $topCustomerByOrders = Customer::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->first();

        $result = [
            'total_customers' => $total,
            'average_orders_per_customer' => $averageOrdersPerCustomer,
            'total_orders' => $totalOrders,
        ];

        if ($breakdown) {
            $result['status_breakdown'] = [
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'suspended' => $suspendedCount,
            ];
        }

        if ($topCustomerByOrders) {
            $result['top_customer'] = [
                'name' => $topCustomerByOrders->name,
                'email' => $topCustomerByOrders->email,
                'order_count' => $topCustomerByOrders->orders_count,
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
            'include_breakdown' => $schema->boolean()->default(true),
        ];
    }
}
