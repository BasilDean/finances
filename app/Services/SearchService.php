<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchService
{
    /**
     * Apply search filters to the given query.
     *
     * @param Builder|BelongsToMany|HasMany $query
     * @param string|null $search
     * @param array $fields
     * @return Builder|BelongsToMany
     */
    public function applySearch(Builder|BelongsToMany|HasMany $query, ?string $search, array $fields): Builder|BelongsToMany|HasMany
    {
        if (!$search || empty($fields)) {
            return $query;
        }

        return $query->where(function ($q) use ($search, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', '%' . $search . '%');
            }
        });
    }
}
