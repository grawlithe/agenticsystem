<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetProductInventory implements Tool
{
    /**
     * Get the tool description.
     */
    public function description(): Stringable|string
    {
        return 'Retrieve product inventory information including total products, stock levels, and reorder alerts for low-stock items.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $showLowStock = $request['show_low_stock'] ?? true;
        $statusFilter = $request['status_filter'] ?? '';

        $query = Product::query();

        if (! empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $products = $query->get();

        $totalProducts = $products->count();
        $totalStockValue = 0;
        $totalStockUnits = 0;
        $lowStockItems = [];
        $outOfStock = 0;

        foreach ($products as $product) {
            $totalStockUnits += $product->stock;
            $totalStockValue += $product->stock * $product->price;

            if ($product->stock === 0) {
                $outOfStock++;
            }

            if ($showLowStock && $product->stock < $product->reorder_level) {
                $lowStockItems[] = [
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'current_stock' => $product->stock,
                    'reorder_level' => $product->reorder_level,
                    'shortage' => $product->reorder_level - $product->stock,
                ];
            }
        }

        $result = [
            'total_products' => $totalProducts,
            'total_stock_units' => $totalStockUnits,
            'total_stock_value' => round($totalStockValue, 2),
            'out_of_stock_count' => $outOfStock,
        ];

        if ($showLowStock) {
            $result['low_stock_count'] = count($lowStockItems);
            $result['low_stock_items'] = $lowStockItems;
        }

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'show_low_stock' => $schema->boolean()->default(true),
            'status_filter' => $schema->string()->description('Optional status filter'),
        ];
    }
}
