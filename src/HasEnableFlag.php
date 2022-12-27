<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasEnableFlag
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeEnabled(Builder $builder): Builder
    {
        return $builder->where('isEnable', true);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeDisabled(Builder $builder): Builder
    {
        return $builder->where('isEnable', false);
    }
}
