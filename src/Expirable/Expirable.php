<?php

namespace EloquentTraits\Expirable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder withExpired(bool $withExpired = true)
 * @method static Builder|\Illuminate\Database\Query\Builder onlyExpired()
 * @method static int expire()
 */
trait Expirable
{
    /**
     * @return void
     */
    public static function bootExpirable(): void
    {
        static::addGlobalScope(new ExpiringScope);
    }

    /**
     * @return void
     */
    public function initializeExpirable(): void
    {
        if (!isset($this->casts[$this->getExpiredAtColumn()])) {
            $this->casts[$this->getExpiredAtColumn()] = 'datetime';
        }

        if (!isset($this->fillable[$this->getExpiredAtColumn()])) {
            $this->fillable[] = $this->getExpiredAtColumn();
        }
    }

    /**
     * @return static
     */
    public function markAsExpired(): static
    {
        $this->{$this->getExpiredAtColumn()} = Carbon::now();
        return $this;
    }

    /**
     * @return static
     */
    public function markAsNotExpired(): static
    {
        $this->{$this->getExpiredAtColumn()} = null;
        return $this;
    }

    /**
     * @return bool
     */
    public function expired(): bool
    {
        $expiredAt = $this->{$this->getExpiredAtColumn()};
        if ($expiredAt instanceof Carbon) {
            return $expiredAt->isPast();
        }
        return false;
    }

    /**
     * @return string
     */
    public function getExpiredAtColumn(): string
    {
        return defined(static::class . '::EXPIRED_AT') ? static::EXPIRED_AT : 'expired_at';
    }

    /**
     * @return string
     */
    public function getQualifiedExpiredAtColumn(): string
    {
        return $this->qualifyColumn($this->getExpiredAtColumn());
    }

    /**
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->{$this->getExpiredAtColumn()}?->isPast();
    }
}
