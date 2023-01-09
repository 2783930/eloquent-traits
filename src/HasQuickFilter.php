<?php

namespace EloquentTraits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property array $quickFilterColumns
 * @property array $filters
 */
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
            if (property_exists($this, 'quickFilterColumns')) {
                foreach ($this->quickFilterColumns as $column) {
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
