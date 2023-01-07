<?php

namespace EloquentTraits\HasCurrent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HasCurrentScope implements Scope
{
    protected array $extensions = ['OnlyCurrent', 'OnlyNotCurrent'];

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
    protected function addOnlyCurrent(Builder $builder): void
    {
        $builder->macro('onlyCurrent', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsCurrentColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyNotCurrent(Builder $builder): void
    {
        $builder->macro('onlyNotCurrent', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsCurrentColumn(), false);
        });
    }
}
