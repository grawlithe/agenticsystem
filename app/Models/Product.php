<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sku', 'name', 'category', 'description', 'price', 'stock', 'reorder_level', 'status'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use Concerns\HasEmbedding, HasFactory;

    protected $hidden = [
        'embedding',
    ];

    /**
     * Build a searchable text representation of the product.
     */
    public function toSearchableText(): string
    {
        return "Product: {$this->name}. Category: {$this->category}. SKU: {$this->sku}. Description: {$this->description}. Price: {$this->price}. Status: {$this->status}.";
    }

    /**
     * Get all order items for this product.
     */
    public function orderItems(): HasMany
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
            'price' => 'decimal:2',
            'status' => 'string',
            'embedding' => 'array',
        ];
    }
}
