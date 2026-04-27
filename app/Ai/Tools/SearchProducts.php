<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Laravel\Ai\Tools\SimilaritySearch;

class SearchProducts extends SimilaritySearch
{
    public function __construct()
    {
        parent::__construct(
            using: fn (string $query) => Product::query()
                ->whereVectorSimilarTo('embedding', $query, 0.6)
                ->limit(15)
                ->get()
                ->map(fn (Product $model) => collect($model->toArray())->except(['embedding'])->all()),
        );

        $this->description = 'Search for products using semantic similarity. Use this for conceptual or meaning-based product queries (e.g. "products for winter", "high-value items").';
    }
}
