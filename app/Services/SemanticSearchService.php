<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class SemanticSearchService
{
    /**
     * Search across all entities.
     *
     * @return array{products: Collection, customers: Collection, orders: Collection}
     */
    public function searchAll(string $query, int $limit = 5): array
    {
        return [
            'products' => $this->searchProducts($query, $limit),
            'customers' => $this->searchCustomers($query, $limit),
            'orders' => $this->searchOrders($query, $limit),
        ];
    }

    /**
     * Search for products.
     */
    public function searchProducts(string $query, int $limit = 5): Collection
    {
        return Product::query()
            ->selectVectorDistance('embedding', $query)
            ->orderBy('embedding_distance')
            ->limit($limit)
            ->get();
    }

    /**
     * Search for customers.
     */
    public function searchCustomers(string $query, int $limit = 5): Collection
    {
        return Customer::query()
            ->selectVectorDistance('embedding', $query)
            ->orderBy('embedding_distance')
            ->limit($limit)
            ->get();
    }

    /**
     * Search for orders.
     */
    public function searchOrders(string $query, int $limit = 5): Collection
    {
        return Order::query()
            ->selectVectorDistance('embedding', $query)
            ->orderBy('embedding_distance')
            ->limit($limit)
            ->get();
    }
}
