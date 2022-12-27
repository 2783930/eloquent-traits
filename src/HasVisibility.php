<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasVisibility
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeVisible(Builder $builder): Builder
    {
        return $builder->where('is_visible', true);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeInvisible(Builder $builder): Builder
    {
        return $builder->where('is_visible', false);
    }
}
