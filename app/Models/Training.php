<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = ['title', 'slug', 'code', 'summary', 'audience', 'prerequisites', 'duration', 'format', 'price', 'program', 'is_published'];
    protected function casts(): array { return ['program' => 'array', 'price' => 'integer', 'is_published' => 'boolean']; }
    public function getRouteKeyName(): string { return 'slug'; }
    public function registrations() { return $this->hasMany(TrainingRegistration::class); }
}
