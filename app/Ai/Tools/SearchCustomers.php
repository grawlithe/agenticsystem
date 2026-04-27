<?php

namespace App\Ai\Tools;

use App\Models\Customer;
use Laravel\Ai\Tools\SimilaritySearch;

class SearchCustomers extends SimilaritySearch
{
    public function __construct()
    {
        parent::__construct(
            using: fn (string $query) => Customer::query()
                ->whereVectorSimilarTo('embedding', $query, 0.6)
                ->limit(15)
                ->get()
                ->map(fn (Customer $model) => collect($model->toArray())->except(['embedding'])->all()),
        );

        $this->description = 'Search for customers using semantic similarity. Use this for conceptual or meaning-based customer queries.';
    }
}
