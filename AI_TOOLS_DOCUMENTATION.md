# AI Business Intelligence Tools

This document describes the AI assistant tools available for querying business data dynamically using semantic search and exact SQL generation.

## Overview

The PersonalAssistant agent has been configured with four powerful core tools that enable natural language queries about any business entity through schema-aware SQL processing and similarity-based vector search.

## Available Tools

### 1. ReadBusinessEntities

**Purpose**: Fetches the comprehensive system database schema, table relationships, constraints, and operational logic.
**When to use**: Under the hood, the AI uses this automatically whenever it needs to precisely map your natural language request to table columns before executing dynamic queries.

### 2. ExecuteSqlQuery

**Purpose**: Execute raw, read-only SQL queries directly against the operational database.
**When to use**: Ideal for exact aggregations (e.g., counting users, summing revenue, finding latest orders, filtering by discrete statuses).

### 3. SearchEntities

**Purpose**: Semantic and contextual vector search across database entities (Customers, Products, Orders).
**When to use**: When exact keywords are missing or the query is ambiguous (e.g., "Find customers who usually buy winter clothing" or "high-risk behavior").

### 4. GetSalesProjection

**Purpose**: Forecast future revenue realistically utilizing historical growth formulas and statistical math.
**When to use**: When asking for future projections, trend forecasting, or long-term growth expectations.

---

## Capabilities

With these 4 flexible tools, the assistant is no longer restricted to hardcoded dashboards! It can dynamically answer:
- "How many active users placed orders yesterday?" (Uses ExecuteSqlQuery)
- "Find the customer who bought the most expensive product" (Uses ExecuteSqlQuery)
- "Are there any products related to outdoor summer camping?" (Uses SearchEntities)
- "What is our revenue footprint going to look like next quarter?" (Uses GetSalesProjection)
