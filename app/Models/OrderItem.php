<?php

namespace App\Models;

use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'product_id', 'quantity', 'unit_price', 'subtotal', 'embedding'])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use Concerns\HasEmbedding, HasFactory;

    protected $hidden = [
        'embedding',
    ];

    /**
     * Get the order this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product for this item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Build a searchable text representation of this order item.
     */
    public function toSearchableText(): string
    {
        $productName = $this->product?->name ?? 'Unknown Product';
        $productCategory = $this->product?->category ?? 'Unknown Category';

        return "Order Item: {$productName}. Category: {$productCategory}. Quantity: {$this->quantity}. Unit Price: {$this->unit_price}. Subtotal: {$this->subtotal}. Order ID: {$this->order_id}.";
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'embedding' => 'array',
        ];
    }
}
