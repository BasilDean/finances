<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes, HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'title',
        'parent_id',
        'sort'
    ];


    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($category) {
            $category->normalized_title = mb_strtolower($category->title);

            $slug = Str::slug($category->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure the slug is unique by including soft deleted items
            while (self::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $category->slug = $slug;
        });

        static::updating(static function ($category) {
            $category->normalized_title = mb_strtolower($category->title);
        });
    }

    public static function getFields(): array
    {
        return [
            'title' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
//            'slug' => [
//                'type' => 'string',
//                'hideOnMobile' => false,
//                'show' => true,
//                'editable' => true,
//            ],
            'sort' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'parent_id' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => self::all(),
                'multiple' => true,
                'showField' => 'title',
                'canBeEmpty' => true,
            ],
            'usage_count' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'children_count' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ]
        ];
    }

    public function expenses(): BelongsToMany
    {
        return $this->belongsToMany(Expense::class);
    }
}
