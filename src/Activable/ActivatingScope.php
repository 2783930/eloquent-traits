<?php

namespace EloquentTraits\Activable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActivatingScope implements Scope
{
    protected array $extensions = ['WithDeactivated'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where(function (Builder $builder) use ($model) {
            $builder->where($model->getQualifiedIsActiveColumn(), true);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
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
     * @return void
     */
    protected function addWithDeactivated(Builder $builder): void
    {
        $builder->macro('withDeactivated', function (Builder $builder, $withDeactivated = true) {
            if ($withDeactivated) {
                return $builder->withoutGlobalScope($this);
            }

            return $builder->where(function ($query) use ($builder) {
                $query->where($builder->getModel()->getIsActiveColumn(), true);
            });
        });
    }

}
