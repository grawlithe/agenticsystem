# Enterprise Intelligent Fabric - Business Entities

This document describes the core business entities of the Intelligent Fabric system, their semantic attributes, database mappings, and relationships.

## Overview

The Enterprise Intelligent Fabric implements three primary business entities:
- **Customer** - Represents individuals or organizations that purchase products
- **Product** - Represents goods available for purchase
- **Order** - Represents transactions initiated by customers
- **OrderItem** - Represents line items in an order (junction entity)

---

## Business Entity Definitions

### 1. Customer Entity

**Purpose**: Manages customer information and maintains relationships with orders.

**Semantic Attributes**:

| Semantic Name | Database Column | Type | Description | Constraints |
|---|---|---|---|---|
| Customer ID | `id` | Unsigned Big Integer | Unique identifier | Primary Key, Auto-increment |
| Full Name | `name` | String | Customer's full name | Required, Max 255 chars |
| Email Address | `email` | String | Unique email identifier | Required, Unique, Max 255 chars |
| Phone Number | `phone` | String | Contact phone number | Optional, Max 255 chars |
| Street Address | `address` | Text | Full street address | Optional, Max 65535 chars |
| City | `city` | String | City of residence | Optional, Max 255 chars |
| Postal Code | `postal_code` | String | ZIP/Postal code | Optional, Max 255 chars |
| Country | `country` | String | Country of residence | Optional, Max 255 chars |
| Status | `status` | Enum | Customer status | One of: active, inactive, suspended |
| Created Timestamp | `created_at` | Timestamp | Record creation time | Auto-set, Nullable |
| Updated Timestamp | `updated_at` | Timestamp | Last modification time | Auto-set, Nullable |

**Model Location**: `app/Models/Customer.php`

**Database Table**: `customers`

**Fillable Attributes**: `name`, `email`, `phone`, `address`, `city`, `postal_code`, `country`, `status`

**Type Casting**: `status` cast as string

**Relationships**:
- **Has Many**: Orders (`orders()`) - One customer can have multiple orders

**Key Methods**:
- `orders()` - Retrieve all orders for this customer

---

### 2. Product Entity

**Purpose**: Manages product catalog with inventory tracking and pricing information.

**Semantic Attributes**:

| Semantic Name | Database Column | Type | Description | Constraints |
|---|---|---|---|---|
| Product ID | `id` | Unsigned Big Integer | Unique identifier | Primary Key, Auto-increment |
| SKU | `sku` | String | Stock Keeping Unit | Required, Unique, Max 255 chars |
| Product Name | `name` | String | Display name | Required, Max 255 chars |
| Category | `category` | String | Product category | Required, Max 255 chars |
| Description | `description` | Text | Detailed product info | Optional, Max 65535 chars |
| Unit Price | `price` | Decimal | Price per unit | Required, 10 digits, 2 decimal places |
| Inventory Stock | `stock` | Integer | Current quantity available | Required, Minimum 0 |
| Reorder Level | `reorder_level` | Integer | Threshold for reordering | Required, Minimum 0, Default 10 |
| Status | `status` | Enum | Product availability | One of: active, inactive, discontinued |
| Created Timestamp | `created_at` | Timestamp | Record creation time | Auto-set, Nullable |
| Updated Timestamp | `updated_at` | Timestamp | Last modification time | Auto-set, Nullable |
| Risk Profile | `risk_profile` | Enum | Risk profile of the product | One of: low, medium, high. Inferred from customer's order history |

**Model Location**: `app/Models/Product.php`

**Database Table**: `products`

**Fillable Attributes**: `sku`, `name`, `description`, `price`, `stock`, `reorder_level`, `status`

**Type Casting**: 
- `price` cast as decimal with 2 places
- `status` cast as string

**Relationships**:
- **Has Many**: OrderItems (`orderItems()`) - One product can appear in multiple orders

**Key Methods**:
- `orderItems()` - Retrieve all order items for this product

---

### 3. Order Entity

**Purpose**: Manages customer transactions and order lifecycle.

**Semantic Attributes**:

| Semantic Name | Database Column | Type | Description | Constraints |
|---|---|---|---|---|
| Order ID | `id` | Unsigned Big Integer | Unique identifier | Primary Key, Auto-increment |
| Customer Reference | `customer_id` | Unsigned Big Integer | Foreign key to Customer | Required, Indexed, Cascades on delete |
| Order Number | `order_number` | String | Human-readable identifier | Required, Unique, Max 255 chars |
| Total Amount | `total_amount` | Decimal | Order subtotal | Required, 10 digits, 2 decimal places |
| Order Status | `status` | Enum | Current order state | One of: pending, processing, shipped, delivered, cancelled |
| Internal Notes | `notes` | Text | Administrative notes | Optional, Max 65535 chars |
| Shipped Timestamp | `shipped_at` | Timestamp | When order was dispatched | Optional, Nullable |
| Delivered Timestamp | `delivered_at` | Timestamp | When order was received | Optional, Nullable |
| Created Timestamp | `created_at` | Timestamp | Record creation time | Auto-set, Nullable |
| Updated Timestamp | `updated_at` | Timestamp | Last modification time | Auto-set, Nullable |

**Model Location**: `app/Models/Order.php`

**Database Table**: `orders`

**Fillable Attributes**: `customer_id`, `order_number`, `total_amount`, `status`, `notes`, `shipped_at`, `delivered_at`

**Type Casting**:
- `total_amount` cast as decimal with 2 places
- `status` cast as string
- `shipped_at` cast as datetime
- `delivered_at` cast as datetime

**Relationships**:
- **Belongs To**: Customer (`customer()`) - Each order belongs to one customer
- **Has Many**: OrderItems (`items()`) - One order contains multiple items

**Key Methods**:
- `customer()` - Get the customer who placed this order
- `items()` - Get all items in this order

---

### 4. OrderItem Entity (Junction)

**Purpose**: Bridges Orders and Products, representing line items with quantity and pricing.

**Semantic Attributes**:

| Semantic Name     | Database Column | Type                 | Description                     | Constraints                           |
|-------------------|-----------------|----------------------|---------------------------------|---------------------------------------|
| Item ID           | `id`            | Unsigned Big Integer | Unique identifier               | Primary Key, Auto-increment           |
| Order Reference   | `order_id`      | Unsigned Big Integer | Foreign key to Order            | Required, Indexed, Cascades on delete |
| Product Reference | `product_id`    | Unsigned Big Integer | Foreign key to Product          | Required, Indexed, Cascades on delete |
| Quantity          | `quantity`      | Integer              | Units ordered                   | Required, Minimum 1                   |
| Unit Price        | `unit_price`    | Decimal              | Price per unit at purchase time | Required, 10 digits, 2 decimal places |
| Subtotal          | `subtotal`      | Decimal              | Quantity × Unit Price           | Required, 10 digits, 2 decimal places |
| Created Timestamp | `created_at`    | Timestamp            | Record creation time            | Auto-set, Nullable                    |
| Updated Timestamp | `updated_at`    | Timestamp            | Last modification time          | Auto-set, Nullable                    |

**Model Location**: `app/Models/OrderItem.php`

**Database Table**: `order_items`

**Fillable Attributes**: `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`

**Type Casting**:
- `unit_price` cast as decimal with 2 places
- `subtotal` cast as decimal with 2 places

**Relationships**:
- **Belongs To**: Order (`order()`) - Each item belongs to exactly one order
- **Belongs To**: Product (`product()`) - Each item references exactly one product

**Key Methods**:
- `order()` - Get the order this item belongs to
- `product()` - Get the product for this item

---

## Entity Relationships Diagram

```
┌──────────────┐          ┌──────────────┐
│  Customer    │          │   Product    │
│──────────────│          │──────────────│
│ id (PK)      │          │ id (PK)      │
│ name         │          │ sku          │
│ email        │          │ name         │
│ phone        │          │ price        │
│ address      │          │ stock        │
│ status       │          │ status       │
└──────────────┘          └──────────────┘
       △                          △
       │ 1                        │ 1
       │ (has many)              │ (has many)
       │                         │
       │ n                       │ n
┌──────┴──────┬──────────────────┴────┐
│    Order    │                       │
│─────────────│            ┌──────────┴──────────┐
│ id (PK)     │            │   OrderItem        │
│ customer_id │────┐       │────────────────────│
│ order_number│    │       │ id (PK)            │
│ total_amount│    │       │ order_id (FK)      │
│ status      │    │       │ product_id (FK)    │
│ shipped_at  │    │       │ quantity           │
│ delivered_at│    │       │ unit_price         │
└─────────────┘    │       │ subtotal           │
                   └───────┤                    │
                           └────────────────────┘
```

---

## Data Flow & Semantic Mappings

### Customer → Order → OrderItem → Product Flow

1. **Customer Creation**: A new customer entity is created with contact and location information
2. **Product Catalog**: Products are maintained as inventory with pricing and stock levels
3. **Order Placement**: Customer initiates an order with a unique order number and initial status of "pending"
4. **Order Items**: Products are added to the order as line items with quantity and pricing snapshot
5. **Order Status Progression**: Order moves through states: pending → processing → shipped → delivered
6. **Inventory Update**: At order completion, product stock should be decremented (future implementation)

---

## Factory & Seeder Information

### Factories
- **CustomerFactory** (`database/factories/CustomerFactory.php`) - Generates realistic test customers
- **ProductFactory** (`database/factories/ProductFactory.php`) - Generates realistic test products
- **OrderFactory** (`database/factories/OrderFactory.php`) - Generates realistic test orders
- **OrderItemFactory** (`database/factories/OrderItemFactory.php`) - Generates realistic test order items

### Seeders
- **CustomerSeeder** (`database/seeders/CustomerSeeder.php`) - Creates 10 sample customers
- **ProductSeeder** (`database/seeders/ProductSeeder.php`) - Creates 25 sample products
- **OrderSeeder** (`database/seeders/OrderSeeder.php`) - Creates orders with items for all customers

Run all seeders via: `php artisan db:seed`

---

## Migration Files

- `database/migrations/2026_04_06_023407_create_customers_table.php`
- `database/migrations/2026_04_06_023412_create_products_table.php`
- `database/migrations/2026_04_06_023421_create_orders_table.php`
- `database/migrations/2026_04_06_023421_create_order_items_table.php`

Run migrations: `php artisan migrate`

---

## Access Pattern Examples

### Query all orders for a customer:
```php
$customer = Customer::find(1);
$orders = $customer->orders()->get();
```

### Query all items in an order with product details:
```php
$order = Order::with('items.product')->find(1);
```

### Calculate total revenue from a product:
```php
$product = Product::find(1);
$totalRevenue = $product->orderItems()->sum('subtotal');
```

### Get orders by status:
```php
$shippedOrders = Order::where('status', 'shipped')->get();
```

---

## Constraints & Business Rules

1. **Customer Email Uniqueness**: Each customer must have a unique email address
2. **Product SKU Uniqueness**: Each product must have a unique SKU
3. **Order Number Uniqueness**: Each order must have a unique order number
4. **Referential Integrity**: Orders reference existing customers; order items reference existing orders and products
5. **Cascading Deletes**: Deleting a customer cascades to their orders; deleting an order cascades to its items
6. **Price Preservation**: OrderItem captures the product price at time of purchase (not live price)

---

## Future Enhancements

- [ ] Inventory tracking with automatic stock decrements on order completion
- [ ] Customer tier classification (Bronze, Silver, Gold)
- [ ] Product categories and attributes
- [ ] Order payment tracking and receipt generation
- [ ] Customer review and rating system
- [ ] Discount codes and promotional pricing
- [ ] Shipment and tracking information
- [ ] Return and refund management
