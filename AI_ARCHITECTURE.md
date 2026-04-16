# AI Business Intelligence Architecture

## System Overview

```text
┌─────────────────────────────────────────────────────────────┐
│                        User Interface                       │
│                   (CLI: php artisan chat)                   │
└────────────────────────────┬────────────────────────────────┘
                             ▼
            ┌────────────────────────────────────┐
            │   PersonalAssistant Agent          │
            │  (Orchestrator & Tool Manager)     │
            └────────────────┬───────────────────┘
                             ▼
            ┌────────────────────────────────────┐
            │       LM Studio (Local LLM)        │
            │         (Gemma 4 & Nomic)          │
            └────────────────┬───────────────────┘
                             ▼
            ┌────────────────────────────────────┐
            │            Tool Suite              │
            ├───────────────┬────────────────────┤
            │ SearchEntities│ ReadBusinessEntities
            │ ExecuteSql    │ GetSalesProjection │
            └───────┬───────┴────────┬───────────┘
                    ▼                ▼
         ┌────────────────┐ ┌──────────────────┐
         │ pgvector Search│ │ PostgreSQL Data  │
         └────────────────┘ └──────────────────┘
```

## Component Architecture

1. **CLI Layer**: `app/Console/Commands/Test.php` provides interactive terminal looping.
2. **AI Layer**: `app/Ai/Agents/PersonalAssistant.php` heavily encourages the LLM to write raw queries instead of relying on rigid internal endpoints.
3. **Database Layer**: PostgreSQL handles both strict relational structures (`customers`, `products`, `orders`) and dynamic pgvector layers (`embedding` columns) generated via `php artisan embeddings:generate`.

## Execution Patterns

Rather than maintaining rigid REST endpoints for common statistic dashboards, the AI now acts autonomously:
- It checks `BUSINESS_ENTITIES.md` via `ReadBusinessEntities`.
- It composes raw SQL queries or invokes a Vector `SearchEntities` match.
- It translates backend data back into natural text dynamically.

This greatly minimizes token load and execution bottleneck times.
