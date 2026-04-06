<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetOrderStatistics implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Retrieve order statistics including total count, revenue, status breakdown, and average order value.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $statusFilter = $request['status_filter'] ?? '';

        $query = Order::query();

        if (! empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $total = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $averageOrderValue = $total > 0 ? round($totalRevenue / $total, 2) : 0;

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusBreakdown = [];

        foreach ($statuses as $status) {
            $statusBreakdown[$status] = Order::where('status', $status)->count();
        }

        $revenueByStatus = [];
        foreach ($statuses as $status) {
            $revenue = Order::where('status', $status)->sum('total_amount');
            $revenueByStatus[$status] = round($revenue, 2);
        }

        $result = [
            'total_orders' => $total,
            'total_revenue' => round($totalRevenue, 2),
            'average_order_value' => $averageOrderValue,
            'status_breakdown' => $statusBreakdown,
            'revenue_by_status' => $revenueByStatus,
        ];

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'status_filter' => $schema->string()->description('Optional status filter'),
        ];
    }
}
