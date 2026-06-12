<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'type',
        'category_id',
        'day_of_month',
        'active',
    ];

    protected $casts = [
        'amount'       => 'float',
        'day_of_month' => 'integer',
        'active'       => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}