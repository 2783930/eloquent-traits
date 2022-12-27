<?php

namespace EloquentTraits\Activable;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder withDeactivated(bool $withDeactivated = true)
 */
trait Activable
{
    #region Boot

    public static function bootActivable(): void
    {
        static::addGlobalScope(new ActivatingScope);
    }

    public function initializeActivable(): void
    {
        if (!isset($this->casts[$this->getIsActiveColumn()])) {
            $this->casts[$this->getIsActiveColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsActiveColumn()])) {
            $this->fillable[] = $this->getIsActiveColumn();
        }
    }

    #endregion

    #region Helpers

    public function markAsActivated(): void
    {
        $this->{$this->getIsActiveColumn()} = true;
        $this->save();
    }

    public function markAsNotActivated(): void
    {
        $this->{$this->getIsActiveColumn()} = false;
        $this->save();
    }

    public function activated(): bool
    {
        return !is_null($this->{$this->getIsActiveColumn()});
    }

    public function getIsActiveColumn(): string
    {
        /** @noinspection PhpUndefinedClassConstantInspection */
        return defined(static::class . '::IS_ACTIVE') ? static::IS_ACTIVE : 'isActive';
    }

    public function getQualifiedIsActiveColumn(): string
    {
        return $this->qualifyColumn($this->getIsActiveColumn());
    }

    #endregion

    #region Scopes

    public function scopeActivated(Builder $builder): Builder
    {
        return $builder->where($this->getIsActiveColumn(), true);
    }

    public function scopeNotActivated(Builder $builder): Builder
    {
        return $builder->whereNull($this->getIsActiveColumn())
                       ->orWhere($this->getIsActiveColumn(), false);
    }

    #endregion
}
