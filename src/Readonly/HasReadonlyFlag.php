<?php

namespace EloquentTraits\Readonly;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder onlyReadonly()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyNotReadonly()
 */
trait HasReadonlyFlag
{
    public static function bootHasReadonlyFlag(): void
    {
        static::addGlobalScope(new HasReadonlyScope);
    }

    public function initializeHasReadonlyFlag(): void
    {
        if (!isset($this->casts[$this->getReadonlyColumn()])) {
            $this->casts[$this->getReadonlyColumn()] = 'boolean';
        }
    }

    public function getReadonlyColumn(): string
    {
        return defined(static::class . '::IS_READONLY') ? static::IS_READONLY : 'is_readonly';
    }

    public function getQualifiedReadonlyColumn(): string
    {
        return $this->qualifyColumn($this->getReadonlyColumn());
    }

    public function scopeWithReadonly(Builder $builder, $withReadonly = true)
    {
        return $builder->when($withReadonly == false, function (Builder $query) {
            return $query->where($this->getReadonlyColumn(), false);
        });
    }

    public function scopeOnlyReadonly(Builder $builder): Builder
    {
        return $builder->where($this->getReadonlyColumn(), true);
    }

    public function scopeWithoutReadonly(Builder $builder): Builder
    {
        return $builder->where($this->getReadonlyColumn(), false);
    }

}
