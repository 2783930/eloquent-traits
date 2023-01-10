<?php

namespace EloquentTraits\Expirable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExpiringScope implements Scope
{
    protected array $extensions = ['WithExpired', 'OnlyExpired', 'Expire'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where(function (Builder $builder) use ($model) {
            $builder
                ->whereNull($model->getQualifiedExpiredAtColumn())
                ->orWhere($model->getQualifiedExpiredAtColumn(), '>', Carbon::now());
        });
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
    protected function addWithExpired(Builder $builder): void
    {
        $builder->macro('withExpired', function (Builder $builder, $withExpired = true) {
            if (!$withExpired) {
                return $builder->withoutExpired();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addOnlyExpired(Builder $builder): void
    {
        $builder->macro('onlyExpired', function (Builder $builder) {
            return $builder->where(function (Builder $builder) {
                $column = $builder->getModel()->getQualifiedExpiredAtColumn();

                $builder->where($column, '>', Carbon::now());
            });
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function addExpire(Builder $builder): void
    {
        $builder->macro('expire', function (Builder $builder) {
            $column = $builder->getModel()->getQualifiedExpiredAtColumn();
            return $builder->update([
                $column => Carbon::now()
            ]);
        });
    }
}
