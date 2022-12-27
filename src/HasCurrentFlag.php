<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasCurrentFlag
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeCurrent(Builder $builder): Builder
    {
        return $builder->where('isCurrent', true);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotCurrent(Builder $builder): Builder
    {
        return $builder->where('isCurrent', false);
    }
}
