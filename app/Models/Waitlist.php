<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    protected $fillable = [
        'product_id',
        'customer_name',
        'wa_number',
        'is_notified',
        'notified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_notified' => 'boolean',
            'notified_at' => 'datetime',
        ];
    }

    /**
     * Get the product that the waitlist is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
