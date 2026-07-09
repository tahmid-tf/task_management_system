<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TaskCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'position',
    ];

    protected static function booted(): void
    {
        static::saving(function (TaskCategory $category): void {
            if (! $category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
