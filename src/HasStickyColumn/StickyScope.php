<?php

namespace EloquentTraits\HasStickyColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StickyScope implements Scope
{
    protected array $extensions = ['OrderBySticky', 'OnlySticky', 'OnlyNotSticky'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderByDesc($model->getQualifiedIsStickyColumn());
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function extend(Builder $builder): void
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|false                          $direction
     * @return void
     */
    protected function addOrderBySticky(Builder $builder, string|false $direction = 'desc'): void
    {
        $builder->macro('orderBySticky', function (Builder $builder) use ($direction) {
            if (!$direction) {
                return $builder->withoutGlobalScope($this);
            }

            return $builder->orderBy($builder->getModel()->getIsStickyColumn(), $direction);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlySticky(Builder $builder): void
    {
        $builder->macro('onlySticky', function (Builder $builder) {
            return $builder->where($builder->getModel()->getIsStickyColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyNotSticky(Builder $builder): void
    {
        $builder->macro('onlyNotSticky', function (Builder $builder) {
            return $builder->where($builder->getModel()->getIsStickyColumn(), false);
        });
    }
}
