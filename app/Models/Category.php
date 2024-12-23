<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'parent_id',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->normalized_title = mb_strtolower($category->title);
        });

        static::updating(function ($category) {
            $category->normalized_title = mb_strtolower($category->title);
        });
    }

    public function expenses(): BelongsToMany
    {
        return $this->belongsToMany(Expense::class);
    }
}
