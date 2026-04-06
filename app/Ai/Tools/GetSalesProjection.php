<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetSalesProjection implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Calculate sales projections for future periods based on historical sales data. Analyzes trends and provides revenue forecasts.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $projectionDays = (int) ($request['projection_days'] ?? 30);
        $historicalDays = (int) ($request['historical_days'] ?? 30);

        // Get historical data
        $historicalStart = Carbon::now()->subDays($historicalDays);
        $historicalOrders = Order::where('created_at', '>=', $historicalStart)
            ->select(['total_amount', 'created_at'])
            ->get();

        $historicalRevenue = $historicalOrders->sum('total_amount');
        $dailyAverage = $historicalDays > 0 ? $historicalRevenue / $historicalDays : 0;
        $orderCount = $historicalOrders->count();
        $averageOrderValue = $orderCount > 0 ? $historicalRevenue / $orderCount : 0;

        // Calculate growth trend
        $midpoint = Carbon::now()->subDays($historicalDays / 2);
        $firstHalf = Order::whereBetween('created_at', [$historicalStart, $midpoint])
            ->sum('total_amount');
        $secondHalf = Order::whereBetween('created_at', [$midpoint, Carbon::now()])
            ->sum('total_amount');

        $growthRate = ($firstHalf > 0) ? (($secondHalf - $firstHalf) / $firstHalf) : 0;

        // Project future sales
        $projectedRevenue = $dailyAverage * $projectionDays;
        $projectedRevenueWithGrowth = $projectedRevenue * (1 + $growthRate);
        $projectedOrders = ($orderCount / $historicalDays) * $projectionDays;

        // Conservative estimate (without aggressive growth assumptions)
        $conservativeProjection = $dailyAverage * $projectionDays;

        // Optimistic estimate (with growth)
        $optimisticProjection = $projectedRevenueWithGrowth;

        $result = [
            'historical_period' => [
                'days' => $historicalDays,
                'total_revenue' => round($historicalRevenue, 2),
                'daily_average' => round($dailyAverage, 2),
                'order_count' => $orderCount,
                'average_order_value' => round($averageOrderValue, 2),
            ],
            'growth_analysis' => [
                'growth_rate_percentage' => round($growthRate * 100, 2),
                'trend' => $growthRate > 0.1 ? 'upward' : ($growthRate < -0.1 ? 'downward' : 'stable'),
            ],
            'projections' => [
                'projection_days' => $projectionDays,
                'conservative_estimate' => round($conservativeProjection, 2),
                'optimistic_estimate' => round($optimisticProjection, 2),
                'projected_order_count' => round($projectedOrders, 0),
                'projected_average_order_value' => round($averageOrderValue, 2),
            ],
            'summary' => 'Based on analysis of the last '.$historicalDays.' days, projected revenue for the next '.$projectionDays.' days ranges from $'.round($conservativeProjection, 2).' (conservative) to $'.round($optimisticProjection, 2).' (if current growth continues).',
        ];

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'projection_days' => $schema->integer()->default(30),
            'historical_days' => $schema->integer()->default(30),
        ];
    }
}
