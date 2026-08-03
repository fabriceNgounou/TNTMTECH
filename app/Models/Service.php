<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'eyebrow', 'summary', 'description', 'deliverables', 'icon', 'image', 'is_published'];
    protected function casts(): array { return ['deliverables' => 'array', 'is_published' => 'boolean']; }
    public function getRouteKeyName(): string { return 'slug'; }
}
