<?php

namespace EloquentTraits\Readonly;

use Illuminate\Database\Eloquent\Builder;

trait HasReadonlyFlag
{
    #region Boot

    public static function bootHasReadonlyFlag(): void
    {
        //
    }

    public function initializeHasReadonlyFlag(): void
    {
        if (!isset($this->casts[$this->getReadonlyColumn()])) {
            $this->casts[$this->getReadonlyColumn()] = 'boolean';
        }
    }

    #endregion

    #region Helpers

    public function getReadonlyColumn(): string
    {
        return defined(static::class . '::IS_READONLY') ? static::IS_READONLY : 'is_readonly';
    }

    public function getQualifiedReadonlyColumn(): string
    {
        return $this->qualifyColumn($this->getReadonlyColumn());
    }

    #endregion

    #region Scopes

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

    #endregion
}
