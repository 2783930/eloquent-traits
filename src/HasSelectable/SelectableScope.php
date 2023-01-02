<?php

namespace EloquentTraits\HasSelectable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SelectableScope implements Scope
{
    protected array $extensions = ['OnlySelectable', 'OnlyNotSelectable'];

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
    protected function addOnlySelectable(Builder $builder): void
    {
        $builder->macro('onlySelectable', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsSelectableColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyNotSelectable(Builder $builder): void
    {
        $builder->macro('onlyNotSelectable', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsSelectableColumn(), false);
        });
    }
}
