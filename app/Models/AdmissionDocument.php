<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionDocument extends Model
{
    protected $fillable = ['admission_id', 'document_type', 'file_path'];
    public function admission() { return $this->belongsTo(Admission::class); }
}
