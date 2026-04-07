<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ReadBusinessEntities implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Read the comprehensive BUSINESS_ENTITIES.md documentation to fully understand the database schema, entity relationships, constraints, and business rules before attempting complex queries or data analysis.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $path = base_path('BUSINESS_ENTITIES.md');

        if (! file_exists($path)) {
            return 'The BUSINESS_ENTITIES.md file could not be found.';
        }

        return file_get_contents($path);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
