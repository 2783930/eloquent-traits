<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

trait HasQuickFilter
{
    /**
     * @param Builder $builder
     * @param         $keyword
     * @return Builder
     */
    public function scopeApplyQuickFilter(Builder $builder, $keyword): Builder
    {
        return $builder->where(function (Builder $builder) use ($keyword) {
            if (property_exists($this, 'quick_filter_columns')) {
                foreach ($this->quick_filter_columns as $column) {
                    $builder->orWhere($column, 'like', "%{$keyword}%");
                }
            }

            if (property_exists($this, 'filters')) {
                foreach ($this->filters as $column => $operand) {
                    $builder->orWhere($column, $operand, $keyword);
                }
            }
        });
    }
}
