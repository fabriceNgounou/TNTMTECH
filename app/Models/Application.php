<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'name', 'email', 'phone', 'city', 'message', 'cv_path', 'status'];
    public function job() { return $this->belongsTo(Job::class); }
}
