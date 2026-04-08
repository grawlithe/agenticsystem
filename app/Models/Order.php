<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['customer_id', 'order_number', 'total_amount', 'status', 'notes', 'shipped_at', 'delivered_at'])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, Concerns\HasEmbedding;

    /**
     * Build a searchable text representation of the order.
     */
    public function toSearchableText(): string
    {
        $items = $this->items()->with('product')->get()->map(function ($item) {
            return "{$item->quantity}x {$item->product->name}";
        })->implode(', ');

        return "Order #{$this->order_number}. Customer: {$this->customer->name}. Status: {$this->status}. Total: {$this->total_amount}. Items: {$items}. Notes: {$this->notes}";
    }

    /**
     * Get the customer that placed this order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'status' => 'string',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }
}
