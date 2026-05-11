<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'price',
        'shopee_link',
        'stock',
        'status',
    ];

    /**
     * Get the media for the product.
     */
    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class);
    }

    /**
     * Get the waitlists for the product.
     */
    public function waitlists(): HasMany
    {
        return $this->hasMany(Waitlist::class);
    }
}
