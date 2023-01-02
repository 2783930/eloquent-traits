<?php

namespace EloquentTraits\Readonly;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReadOnlyScope implements Scope
{
    protected array $extensions = ['OnlyReadonly', 'OnlyNotReadonly'];

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
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addOnlyReadonly(Builder $builder): void
    {
        $builder->macro('onlyReadonly', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedReadonlyColumn(), true);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addOnlyNotReadonly(Builder $builder): void
    {
        $builder->macro('onlyNotReadonly', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedReadonlyColumn(), false);
        });
    }

}
