<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Throwable;

class ExecuteSqlQuery implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return <<<'DESC'
Execute a read-only SQL SELECT query to answer business questions flexibly. The database contains the following tables and columns:
- customers: id, name, email, phone, address, city, postal_code, country, status (active/inactive/suspended).
- products: id, sku, name, description, category, price, stock, reorder_level, status (active/inactive/discontinued).
- orders: id, customer_id, order_number, total_amount, status (pending/processing/shipped/delivered/cancelled), notes, shipped_at, delivered_at.
- order_items: id, order_id, product_id, quantity, unit_price, subtotal.
Always use explicit column definitions and standard SQL (SQLite/MySQL compatible) syntax. Limit queries when possible.
DESC;
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? '';

        if (empty(trim($query))) {
            return json_encode(['error' => 'Query is required.']);
        }

        // Basic safety check; ensure it starts with SELECT
        if (!preg_match('/^\s*select\b/i', $query)) {
            return json_encode(['error' => 'Only SELECT queries are allowed for security reasons.']);
        }

        try {
            $results = DB::select($query);

            return json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('The SQL SELECT query to execute.'),
        ];
    }
}
