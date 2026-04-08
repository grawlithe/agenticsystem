<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchEntities implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search for products, customers, and orders using semantic (meaning-based) search. Use this for conceptual queries like "winter clothing", "active customers in Europe", or "orders with high risk items".';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'];
        $limit = $request['limit'] ?? 5;
        $entityType = $request['entity_type'] ?? 'all';

        $service = app(\App\Services\SemanticSearchService::class);

        $results = match ($entityType) {
            'products' => ['products' => $service->searchProducts($query, $limit)],
            'customers' => ['customers' => $service->searchCustomers($query, $limit)],
            'orders' => ['orders' => $service->searchOrders($query, $limit)],
            default => $service->searchAll($query, $limit),
        };

        return json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('The semantic search query (e.g., "winter gear", "reliable customers")')->required(),
            'limit' => $schema->integer()->description('Number of results per category')->default(5),
            'entity_type' => $schema->string()->enum(['all', 'products', 'customers', 'orders'])->description('Specific entity type to search for')->default('all'),
        ];
    }
}
