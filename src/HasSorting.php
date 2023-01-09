<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasSorting
{
    /**
     * @param Builder $builder
     * @param         $sortField
     * @param         $sortOrder
     * @return Builder
     */
    public function scopeApplySort(Builder $builder, $sortField, $sortOrder): Builder
    {
        if ($sortOrder == "desc") {
            return $builder->orderByDesc($sortField);
        }
        if ($sortOrder == "asc") {
            return $builder->orderBy($sortOrder);
        }

        return $builder;
    }
}
