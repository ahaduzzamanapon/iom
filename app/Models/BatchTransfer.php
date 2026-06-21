<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTransfer extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'user_id', 'from_batch_id', 'to_batch_id', 'reason',
        'status', 'approved_by', 'transferred_at',
    ];

    protected $casts = ['transferred_at' => 'datetime'];

    public function user()      { return $this->belongsTo(User::class); }
    public function fromBatch() { return $this->belongsTo(Batch::class, 'from_batch_id'); }
    public function toBatch()   { return $this->belongsTo(Batch::class, 'to_batch_id'); }
    public function approver()  { return $this->belongsTo(User::class, 'approved_by'); }
}
