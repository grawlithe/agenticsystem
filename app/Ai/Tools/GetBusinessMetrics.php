<?php

namespace App\Ai\Tools;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetBusinessMetrics implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Get comprehensive business metrics and KPIs including customer count, revenue, orders, and inventory value.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        // 1. Extract parameters from the AI request
        $statusFilter = $request['status_filter'] ?? null;
        // Default to true as per your schema
        $showLowStockOnly = $request['show_low_stock'] ?? true;
        $limit = $request['limit'] ?? 10;

        // 2. Apply filters to Queries
        $totalCustomers = Customer::count();

        // Apply status filter if the AI provided one (e.g., "pending", "completed")
        $orderQuery = Order::query();
        if ($statusFilter) {
            $orderQuery->where('status', $statusFilter);
        }

        $totalOrders = (clone $orderQuery)->count();
        $totalRevenue = (clone $orderQuery)->sum('total_amount');

        $totalProducts = Product::count();

        // 3. Handle Inventory with the 'show_low_stock' logic
        $productQuery = Product::query();
        if ($showLowStockOnly) {
            $productQuery->whereRaw('stock < reorder_level');
        }

        $inventoryData = $productQuery->limit($limit)->get();

        $totalInventoryValue = $inventoryData->reduce(
            fn ($carry, $product) => $carry + ($product->stock * $product->price),
            0
        );

        // 4. Calculate Derived Metrics
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $activeCustomers = Customer::where('status', 'active')->count();
        $lowStockCount = Product::whereRaw('stock < reorder_level')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // 5. Build the JSON Response
        $result = [
            'filter_applied' => [
                'status' => $statusFilter ?? 'all',
                'low_stock_only' => $showLowStockOnly,
                'limit_applied' => $limit,
            ],
            'business_overview' => [
                'total_customers' => $totalCustomers,
                'active_customers' => $activeCustomers,
                'total_products' => $totalProducts,
                'filtered_orders_count' => $totalOrders,
            ],
            'financial_metrics' => [
                'revenue_for_selection' => round($totalRevenue, 2),
                'average_order_value' => round($averageOrderValue, 2),
                'inventory_value_of_selection' => round($totalInventoryValue, 2),
            ],
            'operational_status' => [
                'pending_orders_total' => $pendingOrders,
                'low_stock_products_total' => $lowStockCount,
                'health_score' => $this->calculateHealthScore(
                    $activeCustomers,
                    $totalCustomers,
                    $pendingOrders,
                    $lowStockCount
                ),
            ],
        ];

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'status_filter' => $schema->string()
                ->description('Filter by order status (e.g., pending, delivered)'),

            'show_low_stock' => $schema->boolean()
                ->description('Whether to include low stock alerts')
                ->default(true),

            'limit' => $schema->integer()
                ->description('Max results to return')
                ->default(10),
        ];
    }

    /**
     * Calculate a business health score.
     */
    private function calculateHealthScore(int $activeCustomers, int $totalCustomers, int $pendingOrders, int $lowStockProducts): string
    {
        $activeRate = $totalCustomers > 0 ? ($activeCustomers / $totalCustomers) * 100 : 0;
        $score = 50; // Base score

        // Customer activity affects score
        if ($activeRate > 80) {
            $score += 30;
        } elseif ($activeRate > 50) {
            $score += 15;
        }

        // Pending orders
        $score -= min($pendingOrders * 2, 20);

        // Low stock items
        $score -= min($lowStockProducts, 10);

        return round(max(0, min(100, $score)), 1).'%';
    }
}
