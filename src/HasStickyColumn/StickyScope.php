<?php

namespace EloquentTraits\HasStickyColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StickyScope implements Scope
{
    protected array $extensions = ['OrderBySticky', 'WithoutSticky', 'OnlySticky'];

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
     * @param Builder $builder
     * @return void
     */
    protected function addOrderBySticky(Builder $builder): void
    {
        $builder->macro('orderBySticky', function (Builder $builder, $withoutSticky = false) {
            if ($withoutSticky) {
                return $builder->withoutGlobalScope($this);
            }

            return $builder->orderByDesc($builder->getModel()->getIsStickyColumn());
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addWithoutSticky(Builder $builder): void
    {
        $builder->macro('withoutSticky', function (Builder $builder) {
            return $builder->where($builder->getModel()->getIsStickyColumn(), false);
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

}
