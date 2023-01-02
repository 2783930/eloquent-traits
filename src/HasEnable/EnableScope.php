<?php

namespace EloquentTraits\HasEnable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EnableScope implements Scope
{
    protected array $extensions = ['OnlyEnabled', 'OnlyDisabled'];

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
    protected function addOnlyEnabled(Builder $builder): void
    {
        $builder->macro('onlyEnabled', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsEnableColumn(), true);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function addOnlyDisabled(Builder $builder): void
    {
        $builder->macro('onlyDisabled', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedIsEnableColumn(), false);
        });
    }
}
