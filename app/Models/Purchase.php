<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'transaction_id',
        'payment_id',
        'invoice_number',
        'payment_method',
        'status',
        'connections_purchased',
        'payment_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
