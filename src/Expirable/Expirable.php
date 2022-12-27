<?php

namespace EloquentTraits\Expirable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder withExpired(bool $withExpired = true)
 */
trait Expirable
{
    #region Boot

    public static function bootExpirable(): void
    {
        static::addGlobalScope(new ExpiringScope);
    }

    public function initializeExpirable(): void
    {
        if (!isset($this->casts[$this->getExpiredAtColumn()])) {
            $this->casts[$this->getExpiredAtColumn()] = 'datetime';
        }

        if (!isset($this->fillable[$this->getExpiredAtColumn()])) {
            $this->fillable[] = $this->getExpiredAtColumn();
        }
    }

    #endregion

    #region Helpers

    public function markAsExpired(): void
    {
        $this->{$this->getExpiredAtColumn()} = Carbon::now();
        $this->save();
    }

    public function markAsNotExpired(): void
    {
        $this->{$this->getExpiredAtColumn()} = null;
        $this->save();
    }

    public function expired(): bool
    {
        return !is_null($this->{$this->getExpiredAtColumn()});
    }

    public function getExpiredAtColumn(): string
    {
        return defined(static::class . '::EXPIRED_AT') ? static::EXPIRED_AT : 'expiredAt';
    }

    public function getQualifiedExpiredAtColumn(): string
    {
        return $this->qualifyColumn($this->getExpiredAtColumn());
    }

    #endregion

    #region Virtual Attributes

    public function getIsExpiredAttribute(): bool
    {
        return !is_null($this->{$this->getExpiredAtColumn()});
    }

    #endregion

    #region Scopes

    public function scopeExpired(Builder $builder): Builder
    {
        return $builder->whereNotNull($this->getExpiredAtColumn());
    }

    public function scopeNotExpired(Builder $builder): Builder
    {
        return $builder->whereNull($this->getExpiredAtColumn())
                       ->orWhere($this->getExpiredAtColumn(), '>', Carbon::now());
    }

    #endregion
}
