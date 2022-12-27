<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasSorting
{
    /**
     * @param Builder $builder
     * @param         $sort_field
     * @param         $sort_order
     * @return Builder
     */
    public function scopeApplySort(Builder $builder, $sort_field, $sort_order): Builder
    {
        if ($sort_order == "desc") {
            return $builder->orderByDesc($sort_field);
        }
        if ($sort_order == "asc") {
            return $builder->orderBy($sort_field);
        }

        return $builder;
    }
}
