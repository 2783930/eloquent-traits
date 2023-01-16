<?php

namespace EloquentTraits\HasDefault;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HasDefaultScope implements Scope
{
    protected array $extensions = ['OnlyDefault', 'OnlyNotDefault'];

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
    protected function addOnlyDefault(Builder $builder): void
    {
        $builder->macro('onlyDefault', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsDefaultColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyNotDefault(Builder $builder): void
    {
        $builder->macro('onlyNotDefault', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsDefaultColumn(), false);
        });
    }
}
