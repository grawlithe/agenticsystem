<?php

namespace App\Ai\Tools;

use App\Models\OrderItem;
use Laravel\Ai\Tools\SimilaritySearch;

class SearchOrderItems extends SimilaritySearch
{
    public function __construct()
    {
        parent::__construct(
            using: fn (string $query) => OrderItem::query()
                ->whereVectorSimilarTo('embedding', $query, 0.6)
                ->limit(15)
                ->get()
                ->map(fn (OrderItem $model) => collect($model->toArray())->except(['embedding'])->all()),
        );

        $this->description = 'Search for order items using semantic similarity. Use this for conceptual or meaning-based order item queries.';
    }
}
