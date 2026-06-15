<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 'fee_structure_id', 'invoice_number', 'amount', 'discount',
        'paid_amount', 'payment_method', 'transaction_id', 'status', 'due_date', 'paid_at', 'recorded_by'
    ];
    protected $casts = ['paid_at' => 'datetime', 'due_date' => 'date'];

    public function student()      { return $this->belongsTo(User::class, 'student_id'); }
    public function feeStructure() { return $this->belongsTo(FeeStructure::class); }
    public function recorder()     { return $this->belongsTo(User::class, 'recorded_by'); }

    public function getDueAmountAttribute(): float
    {
        return max(0, ($this->amount - $this->discount) - $this->paid_amount);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
