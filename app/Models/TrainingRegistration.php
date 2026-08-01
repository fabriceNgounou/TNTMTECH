<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingRegistration extends Model
{
    protected $fillable = ['training_id', 'name', 'email', 'phone', 'city', 'company', 'message', 'status'];
    public function training() { return $this->belongsTo(Training::class); }
}
