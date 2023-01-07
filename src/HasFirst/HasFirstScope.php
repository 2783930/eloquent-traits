<?php

namespace EloquentTraits\HasFirst;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HasFirstScope implements Scope
{
    protected array $extensions = ['OnlyFirst', 'OnlyNotFirst'];

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
    protected function addOnlyFirst(Builder $builder): void
    {
        $builder->macro('onlyFirst', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsFirstColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyNotFirst(Builder $builder): void
    {
        $builder->macro('onlyNotFirst', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsFirstColumn(), false);
        });
    }
}
