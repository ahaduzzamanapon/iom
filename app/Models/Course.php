<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['name', 'name_bn', 'type', 'duration_years', 'description', 'thumbnail', 'status'];

    public function semesters()   { return $this->hasMany(Semester::class); }
    public function batches()     { return $this->hasMany(Batch::class); }
    public function subjects()    { return $this->hasMany(Subject::class); }
    public function admissions()  { return $this->hasMany(Admission::class); }
    public function feeStructures() { return $this->hasMany(FeeStructure::class); }
}
