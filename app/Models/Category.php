<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
