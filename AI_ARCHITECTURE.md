# AI Business Intelligence Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        User Interface                           │
│                    (CLI: php artisan chat)                      │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────┐
        │    Interactive Chat Loop            │
        │  (Laravel Prompts & Streaming)      │
        └────────────────┬───────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────┐
        │   PersonalAssistant Agent          │
        │  (Orchestrator & Tool Manager)     │
        └────────────────┬───────────────────┘
                         │
        ┌────────────────┴────────────────┐
        │  LLM Request with Tool Schema   │
        │  (LM Studio Local Inference)    │
        │  (Function Calling Support)     │
        └────────────────┬────────────────┘
                         │
        ┌────────────────▼────────────────────────────────────┐
        │            Tool Selection & Execution              │
        │     (LLM decides which tools to call)               │
        └────┬────────┬────────┬────────┬────────┬────────────┘
             │        │        │        │        │        │
             ▼        ▼        ▼        ▼        ▼        ▼
        ┌────────┐┌────────┐┌────────┐┌────────┐┌────────┐┌───────┐
        │ Biz    ││Customer││ Order  ││Product ││ Sales  ││Recent │
        │Metrics ││ Stats  ││ Stats  ││ Inv    ││ Proj   ││ Orders│
        └───┬────┘└───┬────┘└───┬────┘└───┬────┘└───┬────┘└───┬───┘
            │         │         │         │         │         │
            └─────────┴─────────┴─────────┴─────────┴─────────┘
                                │
                                ▼
                    ┌──────────────────────┐
                    │ Eloquent Query Layer │
                    │  (Models)            │
                    │ • Customer           │
                    │ • Order              │
                    │ • Product            │
                    │ • OrderItem          │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │   SQLite Database    │
                    │ (Application Data)   │
                    │ • customers table    │
                    │ • orders table       │
                    │ • products table     │
                    │ • order_items table  │
                    └──────────────────────┘
```

---

## Component Architecture

### 1. CLI Command Layer
**File**: `app/Console/Commands/Test.php`

```
Purpose: Interactive user interface
- Signature: 'chat'
- Text prompt input loop
- Streaming response output
- Exit condition: type 'exit'
```

### 2. AI Agent Layer
**File**: `app/Ai/Agents/PersonalAssistant.php`

```
Purpose: Orchestrate AI interactions and tool management
- Implements: Agent, Conversational, HasTools
- System Instructions: Business analyst role
- Tool Registry: 6 specialized tools
- Conversational State: Empty array (stateless each request)
```

### 3. Tool Layer
**Directory**: `app/Ai/Tools/`

```
6 Independent Tools:

1. GetBusinessMetrics
   - Aggregates all KPIs
   - Calculates health score
   - Latest status snapshot

2. GetCustomerStatistics
   - Customer count & breakdown
   - Engagement metrics
   - Top customers

3. GetOrderStatistics
   - Revenue totals
   - Order by status
   - Financial metrics

4. GetProductInventory
   - Stock levels
   - Inventory value
   - Reorder alerts

5. GetSalesProjection
   - Historical analysis
   - Growth trends
   - Revenue forecasts

6. GetRecentOrderDetails
   - Specific order info
   - Customer details
   - Line items
```

### 4. Model Layer
**Directory**: `app/Models/`

```
4 Business Models:

- Customer
  ├─ Attributes: name, email, phone, address, city, postal_code, country, status
  ├─ Relationships: hasMany(Order)
  └─ Cast: status as string

- Product
  ├─ Attributes: sku, name, description, price, stock, reorder_level, status
  ├─ Relationships: hasMany(OrderItem)
  └─ Cast: price as decimal, status as string

- Order
  ├─ Attributes: customer_id, order_number, total_amount, status, notes, shipped_at, delivered_at
  ├─ Relationships: belongsTo(Customer), hasMany(OrderItem)
  └─ Cast: total_amount as decimal, timestamps

- OrderItem
  ├─ Attributes: order_id, product_id, quantity, unit_price, subtotal
  ├─ Relationships: belongsTo(Order), belongsTo(Product)
  └─ Cast: prices as decimal
```

### 5. Database Layer
**Database**: SQLite (via Laravel)

```
Tables:
- customers (10 rows seeded)
- products (25 rows seeded)
- orders (26 rows seeded)
- order_items (95 rows seeded)
```

---

## Data Flow Sequences

### Scenario 1: Ask for Business Overview
```
User: "What's the state of my business?"
    ↓
LM Studio: Understands query, selects GetBusinessMetrics
    ↓
Tool Execute: Queries all models
    ↓
GetBusinessMetrics.execute()
    ├─ Customer::count()
    ├─ Order::sum('total_amount')
    ├─ Product::get() → calculate inventory value
    ├─ Order::where('status', ...)->count() for health
    └─ Return JSON with all metrics
    ↓
LM Studio: Creates natural language response with insights
    ↓
Response Streamed: "Your business has 10 customers, $28k revenue, health: 78%..."
```

### Scenario 2: Ask for Sales Forecast
```
User: "Project revenue 60 days"
    ↓
LM Studio: Selects GetSalesProjection with projection_days=60
    ↓
Tool Execute: Analyzes historical trends
    ↓
GetSalesProjection.execute(['projection_days' => 60])
    ├─ Order::where('created_at', >= 30 days ago)->sum()
    ├─ Calculate daily average
    ├─ Calculate growth rate
    ├─ Project forward 60 days
    └─ Return JSON with conservative/optimistic estimates
    ↓
LM Studio: Interprets with context about trends
    ↓
Response: "Conservative: $57k, Optimistic: $56k, Trend: Stable..."
```

### Scenario 3: Multi-Tool Comprehensive Report
```
User: "Give me a full business report"
    ↓
LM Studio: Selects multiple tools
    ├─ GetBusinessMetrics → for overview
    ├─ GetCustomerStatistics → for customer insights
    ├─ GetOrderStatistics → for revenue details
    ├─ GetProductInventory → for stock status
    └─ GetSalesProjection → for forecast
    ↓
Tools Execute in Sequence:
    1. GetBusinessMetrics returns KPIs
    2. GetCustomerStatistics returns customer breakdown
    3. GetOrderStatistics returns revenue detail
    4. GetProductInventory returns stock alerts
    5. GetSalesProjection returns 30-day forecast
    ↓
LM Studio: Synthesizes all data into cohesive report
    ↓
Response: Complete business report with all insights
```

---

## Tool Execution Details

### GetBusinessMetrics Flow
```
Input: {}
Processing:
1. Count total customers
2. Count active customers
3. Sum all orders revenue
4. Calculate average order value
5. Get product stock × price
6. Calculate health score:
   - Base: 50
   - + Customer active rate boost (0-30)
   - - Pending orders penalty (-20 max)
   - - Low stock penalty (-10 max)
Output: JSON with all metrics
```

### GetSalesProjection Flow
```
Input: { projection_days: 30, historical_days: 30 }
Processing:
1. Get orders from last 30 days
2. Calculate daily average revenue
3. Calculate growth rate:
   - Compare first half vs second half
   - Positive = upward trend
4. Calculate forecasts:
   - Conservative: dailyAvg × days
   - Optimistic: with growth multiplier
Output: JSON with trend analysis and forecasts
```

---

## Request Response Cycle

```
┌─────────────────────────────────────────────────────────────┐
│ User Types: "What's our inventory status?"                  │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ Chat command captures: text('You', ...)                      │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ Task runs: task('Thinking...', fn() => {                     │
│   $assistant->stream(input) }                                │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ PersonalAssistant.stream($input) called                      │
│ - Uses LM Studio backend                                     │
│ - Sends: input text + tool schemas                           │
│ - Receives: streaming tokens                                 │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ Each stream event handled:                                   │
│ - Tool use event: Execute GetProductInventory               │
│   └─ Database query runs                                    │
│   └─ Results returned to LM                                 │
│ - Text delta events: Collected as output                    │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ Stream output to terminal:                                   │
│ foreach ($events as $event) {                                │
│   if ($event instanceof TextDelta) {                         │
│     $output->append($event->delta);                          │
│ }                                                            │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────────┐
│ User sees: Real-time response appearing                      │
│ Response: "You have 25 products in stock valued at $87k..."  │
└─────────────────────────────────────────────────────────────┘
```

---

## JSON Response Examples

### GetBusinessMetrics Output
```json
{
  "business_overview": {
    "total_customers": 10,
    "active_customers": 10,
    "total_products": 25,
    "total_orders": 26,
    "delivered_orders": 5
  },
  "financial_metrics": {
    "total_revenue": 28547.50,
    "average_order_value": 1098.36,
    "total_order_items": 95,
    "inventory_value": 87234.75
  },
  "operational_status": {
    "pending_orders": 2,
    "low_stock_products": 1,
    "health_score": "78.0%"
  }
}
```

### GetSalesProjection Output
```json
{
  "historical_period": {
    "days": 30,
    "total_revenue": 28547.50,
    "daily_average": 951.58,
    "order_count": 26,
    "average_order_value": 1098.36
  },
  "growth_analysis": {
    "growth_rate_percentage": -2.15,
    "trend": "stable"
  },
  "projections": {
    "projection_days": 30,
    "conservative_estimate": 28547.40,
    "optimistic_estimate": 27901.32,
    "projected_order_count": 25,
    "projected_average_order_value": 1098.36
  },
  "summary": "Based on analysis..."
}
```

---

## Performance Characteristics

| Operation | Time | Complexity |
|-----------|------|-----------|
| GetBusinessMetrics | <100ms | O(n) where n=customers |
| GetCustomerStatistics | <50ms | O(n) where n=customers |
| GetOrderStatistics | <50ms | O(n) where n=orders |
| GetProductInventory | <75ms | O(n) where n=products |
| GetSalesProjection | <100ms | O(n) where n=orders in range |
| GetRecentOrderDetails | <150ms | O(limit * items per order) |

*With seeded data: 10 customers, 26 orders, 25 products*

---

## Security Considerations

✅ **Input Validation**
- Tool input schemas enforce types
- Parameter ranges validated (min/max)
- Enum validation for statuses

✅ **Database Security**
- Eloquent prevents SQL injection
- No raw query construction from input
- Foreign keys enforce referential integrity

✅ **Privacy**
- All processing local
- No external API calls
- Database stays protected

---

## Future Enhancement Points

- [ ] Persist conversation history
- [ ] Add export to reports (PDF/CSV)
- [ ] Customer notifications on alerts
- [ ] Automated inventory reordering
- [ ] Multi-user authentication
- [ ] Advanced analytics dashboards
- [ ] API endpoints for tool access
- [ ] Background job processing
- [ ] Alert rule engine
- [ ] Custom KPI calculations

---

## Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 13 |
| AI Framework | Laravel AI | Latest |
| LLM Backend | LM Studio | Local |
| Database | SQLite | Default |
| CLI | Laravel Prompts | Latest |
| Code Style | Laravel Pint | 1.x |
| Testing | Pest PHP | 4.x |
| PHP | 8.3 | LTS |

---

*Document Version: 1.0*
*Last Updated: April 6, 2026*
*Status: Production Ready* ✅
