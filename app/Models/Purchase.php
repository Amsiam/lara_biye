<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    // Payment Stage Constants
    public const STAGE_INITIATED = 'initiated';
    public const STAGE_PENDING = 'pending';
    public const STAGE_COMPLETED = 'completed';
    public const STAGE_FAILED = 'failed';
    public const STAGE_REFUNDED = 'refunded';
    public const STAGE_CANCELLED = 'cancelled';

    // Payment Status Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'transaction_id',
        'payment_id',
        'invoice_number',
        'payment_method',
        'status',
        'payment_stage',
        'connections_purchased',
        'connections_applied',
        'connections_applied_at',
        'is_refunded',
        'refunded_at',
        'refund_transaction_id',
        'refund_amount',
        'error_message',
        'payment_initiated_at',
        'payment_completed_at',
        'payment_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_response' => 'array',
        'connections_applied' => 'boolean',
        'is_refunded' => 'boolean',
        'connections_applied_at' => 'datetime',
        'refunded_at' => 'datetime',
        'payment_initiated_at' => 'datetime',
        'payment_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // Query Scopes
    public function scopeCompleted($query)
    {
        return $query->where('payment_stage', self::STAGE_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('payment_stage', self::STAGE_PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_stage', self::STAGE_FAILED);
    }

    public function scopeRefunded($query)
    {
        return $query->where('is_refunded', true);
    }

    public function scopeConnectionsApplied($query)
    {
        return $query->where('connections_applied', true);
    }

    public function scopeConnectionsNotApplied($query)
    {
        return $query->where('connections_applied', false);
    }

    // Helper Methods
    public function isCompleted(): bool
    {
        return $this->payment_stage === self::STAGE_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->payment_stage === self::STAGE_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->payment_stage === self::STAGE_FAILED;
    }

    public function isRefunded(): bool
    {
        return $this->is_refunded;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'payment_stage' => self::STAGE_COMPLETED,
            'status' => self::STATUS_COMPLETED,
            'payment_completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage = null): void
    {
        $this->update([
            'payment_stage' => self::STAGE_FAILED,
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    public function applyConnections(): bool
    {
        if ($this->connections_applied) {
            return false; // Already applied
        }

        if (!$this->isCompleted()) {
            return false; // Can only apply connections for completed payments
        }

        $userConnection = Connection::where('user_id', $this->user_id)->first();
        if (!$userConnection) {
            $userConnection = new Connection();
            $userConnection->user_id = $this->user_id;
            $userConnection->setAttribute('connection', 0);
        }

        // Ensure connection is treated as integer - use getAttribute to avoid conflict with reserved 'connection' property
        $currentConnections = (int) ($userConnection->getAttribute('connection') ?? 0);
        $newConnections = (int) $this->connections_purchased;
        $userConnection->setAttribute('connection', $currentConnections + $newConnections);
        $userConnection->save();

        $this->update([
            'connections_applied' => true,
            'connections_applied_at' => now(),
        ]);

        return true;
    }
}
