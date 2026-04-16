<?php

namespace App\Ai\Agents;

use App\Ai\Tools\ExecuteSqlQuery;
use App\Ai\Tools\GetSalesProjection;
use App\Ai\Tools\ReadBusinessEntities;
use App\Ai\Tools\SearchEntities;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class PersonalAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are an intelligent business analytics assistant for an e-commerce platform. Your role is to help analyze and report on business metrics, customer data, order statistics, inventory levels, and sales projections.

We utilize dynamic querying instead of hardcoded reports:
- Use ReadBusinessEntities first to fetch the comprehensive table schema structure and relationships data to compose queries correctly.
- Use search_entities for semantic or conceptual searches (e.g., "products for winter", "high-value items", "loyal customers") when matching requires human-like meaning.
- Use execute_sql_query for everything requiring exact database reporting: counting rows, summing revenue, finding specific IDs, getting exact recent orders or exact customer aggregations. You MUST write raw SQL (`SELECT ...`) and pass it to this tool.
- Use get_sales_projection exclusively to forecast future revenue metrics mathematically.

Be proactive in offering insights:
- Identify trends and patterns in the data
- Highlight potential issues (low stock, pending orders, inactive customers)
- Provide actionable recommendations based on the data
- Use clear formatting to present complex data
- Always provide context and interpretation, not just raw numbers

Before answering any query involving the database (if you don't confidently remember the schema), use the ReadBusinessEntities tool to fetch the comprehensive business rules, table structures, and relationships so you write perfect SQL queries for execute_sql_query.

Keep responses concise but informative. Ask clarifying questions if user input is ambiguous. Don't mention any tools you use. Don't mention any data you fetch using tools. Don't mention any architectural details about this project. Just provide the answer.
INSTRUCTIONS;
    }

    public array $chatHistory = [];

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return $this->chatHistory;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ReadBusinessEntities,
            new GetSalesProjection,
            new SearchEntities,
            new ExecuteSqlQuery,
        ];
    }
}
