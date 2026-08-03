<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title', 'slug', 'city', 'contract_type', 'description', 'missions', 'deadline', 'is_published'];
    protected function casts(): array { return ['missions' => 'array', 'deadline' => 'date', 'is_published' => 'boolean']; }
    public function getRouteKeyName(): string { return 'slug'; }
}
