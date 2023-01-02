<?php

namespace EloquentTraits\Publishable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishingScope implements Scope
{
    protected array $extensions = ['WithUnpublished', 'OnlyUnpublished'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder
            ->where(function (Builder $builder) use ($model) {
                $builder
                    ->whereNotNull($model->getQualifiedPublishedAtColumn())
                    ->where($model->getQualifiedPublishedAtColumn(), '<', Carbon::now());
            })
            ->orderByDesc($model->getQualifiedPublishedAtColumn());
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
    protected function addWithUnpublished(Builder $builder): void
    {
        $builder->macro('withUnpublished', function (Builder $builder, $withUnpublished = true) {
            if (!$withUnpublished) {
                return $builder->withoutUnpublished($this);
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addOnlyUnpublished(Builder $builder): void
    {
        $builder->macro('onlyUnpublished', function (Builder $builder) {
            return $builder->where(function (Builder $builder) {
                $publishedAt = $builder->getModel()->getQualifiedPublishedAtColumn();
                $builder
                    ->whereNull($publishedAt)
                    ->orWhere($publishedAt, '<', Carbon::now());
            });
        });
    }

}
