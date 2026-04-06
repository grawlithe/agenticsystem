# AI Business Assistant Setup & Usage Guide

## Quick Start

Your AI Business Assistant is now fully configured and ready to use! The system includes 6 powerful database query tools that enable the AI to analyze your business data intelligently.

## Starting the Chat

To interact with your AI Business Assistant:

```bash
php artisan chat
```

This will start an interactive chat session where you can ask questions like:

```
You: What's the current state of my business?
You: How many customers do we have and what's their status?
You: Show me recent orders with details
You: What are the sales projections for the next 30 days?
You: Which products are low on stock?
You: How much total revenue have we generated?
You: exit
```

Type "exit" to quit the chat session.

---

## What the AI Can Do

The AI assistant has access to these database query tools:

### 1. **Business Metrics Dashboard** 
   Get comprehensive KPIs, health scores, customer counts, revenue, and inventory value.
   - Tools used: `get_business_metrics`
   - Ask: "Give me a business overview" or "What's our health score?"

### 2. **Customer Analysis**
   Analyze your customer base including total counts, status breakdown, and top customers.
   - Tools used: `get_customer_statistics`
   - Ask: "How many active customers do we have?" or "Show customer stats"

### 3. **Order & Revenue Analysis**
   Understand your orders by status and analyze revenue distribution.
   - Tools used: `get_order_statistics`
   - Ask: "How much revenue have we made?" or "Show order breakdown by status"

### 4. **Inventory Management**
   Monitor stock levels, identify low-stock items, and track inventory value.
   - Tools used: `get_product_inventory`
   - Ask: "What products need reordering?" or "How much is our inventory worth?"

### 5. **Sales Projections**
   Forecast future revenue based on historical trends and growth analysis.
   - Tools used: `get_sales_projection`
   - Ask: "Project sales for 60 days" or "What's our revenue forecast?"

### 6. **Recent Order Details**
   Access specific order information including customer details and line items.
   - Tools used: `get_recent_order_details`
   - Ask: "Show me the last 5 orders" or "Display pending orders"

---

## Example Conversations

### Example 1: Business Overview
```
You: What's the state of my business?

AI: Based on my analysis, here's your business snapshot:
- 10 total customers with 10 active (100% active rate)
- Generated $28,547 in total revenue across 26 orders
- Average order value: $1,098
- 25 products in inventory valued at $87,234
- Business Health Score: 78% (GOOD)
- Areas of attention: 2 pending orders, 1 product low on stock
```

### Example 2: Customer Deep Dive
```
You: Show me customer statistics with breakdown

AI: Customer Analysis:
- Total Customers: 10
- Status Breakdown:
  - Active: 10 (100%)
  - Inactive: 0
  - Suspended: 0
- Total Orders: 26
- Average Orders per Customer: 2.6
- Top Customer: John Smith (john@example.com) with 5 orders
```

### Example 3: Inventory Management
```
You: What inventory needs attention?

AI: Inventory Status:
- Total Products: 25
- Total Stock Value: $87,234
- Out of Stock: 0 products
- Low Stock Alert: 1 product

Products Needing Reorder:
1. SKU-1234ff - Widget Pro
   Current: 3 units | Reorder Level: 10 | Needed: 7 more units
```

### Example 4: Sales Forecast
```
You: Give me a 60-day revenue projection

AI: Sales Forecast (60 days):
Historical Analysis (Last 30 days):
- Total Revenue: $28,547
- Daily Average: $952
- Order Count: 26
- Average Order Value: $1,098
- Growth Trend: Stable (-2%)

Projections:
- Conservative: $57,144 (without growth acceleration)
- Optimistic: $56,051 (if current trends continue)
- Projected Orders: 52-55
```

---

## Architecture

### Tool Registry
The PersonalAssistant agent has 6 registered tools for database queries:

| Tool | Function | Parameters |
|------|----------|-----------|
| `get_business_metrics` | Get KPIs and health score | None |
| `get_customer_statistics` | Customer analysis | `include_breakdown: bool` |
| `get_order_statistics` | Order and revenue data | `status_filter: enum` |
| `get_product_inventory` | Stock and reorder info | `show_low_stock: bool`, `status_filter: enum` |
| `get_sales_projection` | Revenue forecasting | `projection_days: int`, `historical_days: int` |
| `get_recent_order_details` | Specific order data | `limit: int`, `status: enum` |

### Component Locations

**Chat Command**:
- [app/Console/Commands/Test.php](app/Console/Commands/Test.php)

**AI Agent**:
- [app/Ai/Agents/PersonalAssistant.php](app/Ai/Agents/PersonalAssistant.php)

**Database Tools** (in [app/Ai/Tools/](app/Ai/Tools/)):
- GetBusinessMetrics.php
- GetCustomerStatistics.php
- GetOrderStatistics.php  
- GetProductInventory.php
- GetSalesProjection.php
- GetRecentOrderDetails.php

---

## Running Your First Query

```bash
# Start the interactive chat
php artisan chat

# Example session:
You: What's our current revenue?
AI: [Calls get_order_statistics and analyzes your data]

You: Show me low stock items
AI: [Calls get_product_inventory with filters]

You: Can you project next quarter sales?
AI: [Calls get_sales_projection for 90-day forecast]

You: exit
```

---

## Features

✨ **Smart Features**:
- 🎯 Multi-tool analysis for comprehensive insights
- 📊 Intelligent interpretation and recommendations
- 🔍 Proactive issue identification
- 📈 Historical trend analysis and forecasting
- 💾 Complete data privacy with local LM Studio
- 🚀 Natural language interface to complex queries

---

## Documentation

- **AI Tools Reference**: [AI_TOOLS_DOCUMENTATION.md](../AI_TOOLS_DOCUMENTATION.md)
- **Business Entities**: [BUSINESS_ENTITIES.md](../BUSINESS_ENTITIES.md)
- **Setup Guide**: [AI_SETUP_GUIDE.md](../AI_SETUP_GUIDE.md)

---

## Quick Troubleshooting

| Issue | Solution |
|-------|----------|
| Chat command not found | Run `php artisan list` to verify registration |
| LM Studio connection error | Ensure LM Studio is running on configured host/port |
| No tool responses | Check database: `php artisan migrate` and verify models |
| Empty results | Verify seeders ran: `php artisan db:seed` |

---

Ready to analyze your business? Start with:
```bash
php artisan chat
```

Then ask: "What's my business health score?"
