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
Execute a read-only SQL SELECT query to answer business questions flexibly. Always use read_business_entities tool first if you do not know the exact database schema, tables, or columns.
Use this tool for hard aggregations (COUNT, SUM, AVG), sorting (ORDER BY), limits, or specific ID selections. 
Always use standard SQL (SQLite/MySQL/PostgreSQL compatible) syntax. Do not output anything besides the valid SQL query string.
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
        if (! preg_match('/^\s*select\b/i', $query)) {
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
