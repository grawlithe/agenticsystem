<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Laravel\Ai\Tools\SimilaritySearch;

class SearchOrders extends SimilaritySearch
{
    public function __construct()
    {
        parent::__construct(
            using: fn (string $query) => Order::query()
                ->whereVectorSimilarTo('embedding', $query, 0.6)
                ->limit(15)
                ->get()
                ->map(fn (Order $model) => collect($model->toArray())->except(['embedding'])->all()),
        );

        $this->description = 'Search for orders using semantic similarity. Use this for conceptual or meaning-based order queries.';
    }
}
