<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'phone', 'address', 'city', 'postal_code', 'country', 'status'])]

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use Concerns\HasEmbedding, HasFactory;

    /**
     * Build a searchable text representation of the customer.
     */
    public function toSearchableText(): string
    {
        return "Customer: {$this->name}. Email: {$this->email}. Phone: {$this->phone}. Location: {$this->city}, {$this->country}. Address: {$this->address}, {$this->postal_code}. Status: {$this->status}.";
    }

    /**
     * Get all orders belonging to this customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
            'embedding' => 'array',
        ];
    }
}
