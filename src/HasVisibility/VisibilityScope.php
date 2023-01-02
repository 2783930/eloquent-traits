<?php

namespace EloquentTraits\HasVisibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VisibilityScope implements Scope
{
    protected array $extensions = ['OnlyVisible', 'OnlyHidden'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
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
    protected function addOnlyVisible(Builder $builder): void
    {
        $builder->macro('onlyVisible', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsVisibleColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyHidden(Builder $builder): void
    {
        $builder->macro('onlyHidden', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsVisibleColumn(), false);
        });
    }
}
