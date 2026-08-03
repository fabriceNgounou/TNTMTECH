<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $fillable = ['reference', 'name', 'company', 'email', 'phone', 'city', 'service', 'description', 'budget', 'deadline', 'attachment', 'status', 'notes'];
    protected function casts(): array { return ['deadline' => 'date']; }
}
