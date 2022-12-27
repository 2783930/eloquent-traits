<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasSelectable
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeSelectable(Builder $builder): Builder
    {
        return $builder->where('isSelectable', true);
    }
}
