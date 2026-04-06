# AI Business Intelligence Tools

This document describes the AI assistant tools available for querying business data and making intelligent decisions about your enterprise.

## Overview

The PersonalAssistant agent has been configured with six powerful database query tools that enable natural language queries about business metrics, customers, orders, inventory, and sales projections.

## Available Tools

### 1. GetBusinessMetrics

**Purpose**: Retrieve comprehensive business health metrics and KPIs.

**What it provides**:
- Total and active customer counts
- Product inventory count
- Order statistics (total and delivered)
- Total revenue and average order value
- Inventory value (stock × price)
- Operational status (pending orders, low stock alerts)
- Calculated business health score

**When to use**: Ask for an overall business status or complete dashboard view.

**Example usage**:
```
"What's the overall state of my business?"
"Give me a business health report"
"I need a comprehensive business overview"
```

---

### 2. GetCustomerStatistics

**Purpose**: Analyze customer base composition and engagement.

**Input parameters**:
- `include_breakdown` (boolean, default: true) - Include status breakdown by customer status

**What it provides**:
- Total customer count
- Customer status breakdown (active, inactive, suspended)
- Total orders across all customers
- Average orders per customer
- Top customer by order count with contact info

**When to use**: Analyze customer base health, identify top customers, or understand customer distribution.

**Example usage**:
```
"How many customers do we have?"
"Show me the customer statistics"
"Which customer has placed the most orders?"
"What's our customer base breakdown?"
```

---

### 3. GetOrderStatistics

**Purpose**: Analyze order trends, revenue, and status distribution.

**Input parameters**:
- `status_filter` (string) - Filter by status: 'pending', 'processing', 'shipped', 'delivered', or 'cancelled'

**What it provides**:
- Total number of orders
- Total revenue
- Average order value
- Order count by status (pending, processing, shipped, delivered, cancelled)
- Revenue breakdown by order status

**When to use**: Understand order flow, revenue distribution, or specific order status trends.

**Example usage**:
```
"How much revenue have we generated?"
"Show me order statistics"
"How many orders are pending?"
"What's the breakdown of orders by status?"
"How much revenue came from delivered orders?"
```

---

### 4. GetProductInventory

**Purpose**: Monitor inventory levels, stock value, and reorder alerts.

**Input parameters**:
- `show_low_stock` (boolean, default: true) - Include products below reorder level
- `status_filter` (string) - Filter by status: 'active', 'inactive', or 'discontinued'

**What it provides**:
- Total product count
- Total inventory units in stock
- Total inventory value (stock × price)
- Out of stock count
- Low stock products with shortages
- Products below reorder threshold

**When to use**: Check inventory status, identify reorder needs, or assess stock value.

**Example usage**:
```
"What's our inventory status?"
"Show me products that need reordering"
"How many items are out of stock?"
"What's the total value of our inventory?"
"Which products are low on stock?"
```

---

### 5. GetSalesProjection

**Purpose**: Forecast future revenue based on historical trends.

**Input parameters**:
- `projection_days` (integer, 1-365, default: 30) - Number of days to project forward
- `historical_days` (integer, 7-365, default: 30) - Historical data period to analyze

**What it provides**:
- Historical sales data (revenue, orders, average order value)
- Growth rate percentage and trend direction (upward/downward/stable)
- Conservative revenue estimate (without growth)
- Optimistic revenue estimate (with growth)
- Projected order count
- Summary interpretation

**When to use**: Forecast future revenue, understand sales trends, plan inventory based on projections.

**Example usage**:
```
"What are next month's sales projections?"
"Project sales for the next 90 days"
"What's our sales growth trend?"
"Give me a revenue forecast"
"Based on current trends, what will we sell next quarter?"
```

---

### 6. GetRecentOrderDetails

**Purpose**: Access detailed information about recent orders including customer and product data.

**Input parameters**:
- `limit` (integer, 1-50, default: 5) - Number of recent orders to retrieve
- `status` (string) - Filter by order status

**What it provides**:
- Customer name and email
- Customer location (city)
- Order number and total amount
- Order status and timestamps
- Line items with product details (SKU, name, quantity, pricing)
- Shipping timestamps

**When to use**: Review specific orders, audit customer activity, analyze recent trends, investigate issues.

**Example usage**:
```
"Show me the last 10 orders"
"What are the most recent orders?"
"Show me pending orders"
"Display the last 3 delivered orders with details"
```

---

## Example Natural Language Queries

### Business Overview
```
Assistant: "What's the overall state of my business right now?"
AI: Uses get_business_metrics to provide comprehensive dashboard with health score
```

### Customer Analysis
```
Assistant: "How's our customer base doing? Show me the breakdown."
AI: Uses get_customer_statistics with breakdown enabled
```

### Revenue Insights
```
Assistant: "How much have we made in total revenue and what's our average order value?"
AI: Uses get_order_statistics to provide revenue and AOV metrics
```

### Inventory Management
```
Assistant: "What products are low on stock and need reordering?"
AI: Uses get_product_inventory with show_low_stock enabled to identify shortages
```

### Sales Forecasting
```
Assistant: "Project our revenue for the next 60 days based on current trends"
AI: Uses get_sales_projection with 60 day horizon
```

### Order Investigation
```
Assistant: "Show me the last 5 delivered orders with full details"
AI: Uses get_recent_order_details filtered by 'delivered' status
```

### Comprehensive Business Report
```
Assistant: "Give me a full business report - customers, orders, inventory, and sales forecast"
AI: Calls multiple tools to compile comprehensive report
```

---

## How to Access the Tools

### Using the Chat CLI Command

```bash
php artisan chat
```

This starts an interactive chat session with the AI assistant. Simply type your questions naturally:

```
You: What's the state of my business?
Assistant: [Analyzes data and provides insights]

You: Show me customer statistics
Assistant: [Retrieves and formats customer data]

You: What's our 30-day revenue projection?
Assistant: [Calculates and forecasts sales]

You: exit
```

---

## Tool Capabilities Summary

| Tool | Data Returned | Best For |
|---|---|---|
| GetBusinessMetrics | KPIs, health score | Dashboard/overview |
| GetCustomerStatistics | Customer counts, top customers | Customer analysis |
| GetOrderStatistics | Revenue, order status breakdown | Financial analysis |
| GetProductInventory | Stock levels, reorder alerts | Inventory management |
| GetSalesProjection | Revenue forecast, growth trends | Planning/forecasting |
| GetRecentOrderDetails | Specific order information | Operational details |

---

## Intelligent Features

The PersonalAssistant is programmed to:

1. **Proactively identify issues**: Highlights pending orders, low stock items, inactive customers
2. **Provide context**: Doesn't just return numbers, but interprets trends and patterns
3. **Offer recommendations**: Suggests actions based on data analysis
4. **Format data clearly**: Uses JSON responses that can be parsed and displayed nicely
5. **Ask clarifying questions**: When user input is ambiguous
6. **Use multiple tools**: Can combine data from multiple tools for comprehensive reports

---

## Data Interpretation Guidelines

The assistant interprets data as follows:

- **Growth Rate**: 
  - > 10% = Upward trend
  - (-10% to 10%) = Stable
  - < -10% = Downward trend

- **Business Health Score**: 
  - > 80 = Excellent
  - 60-80 = Good
  - 40-60 = Fair
  - < 40 = Needs attention

- **Stock Status**:
  - Low stock = Current stock < Reorder level
  - Out of stock = Current stock = 0

---

## Integration with LM Studio

Your AI assistant is configured to use LM Studio as the LLM backend, allowing you to:
- Run local inference without cloud dependencies
- Maintain complete data privacy
- Use open-source models
- Have full control over the AI experience

The tools are designed to work with any LM that understands function calling and JSON schemas.
