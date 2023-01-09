<?php

namespace EloquentTraits\Activable;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder withDeactivated(bool $withDeactivated = true)
 * @method static Builder|\Illuminate\Database\Query\Builder onlyDeactivated()
 */
trait Activable
{
    /**
     * @return void
     */
    public static function bootActivable(): void
    {
        static::addGlobalScope(new ActivatingScope);
    }

    /**
     * @return void
     */
    public function initializeActivable(): void
    {
        if (!isset($this->casts[$this->getIsActiveColumn()])) {
            $this->casts[$this->getIsActiveColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsActiveColumn()])) {
            $this->fillable[] = $this->getIsActiveColumn();
        }
    }

    /**
     * @return static
     */
    public function markAsActivated(): static
    {
        $this->{$this->getIsActiveColumn()} = true;
        return $this;
    }

    /**
     * @return static
     */
    public function markAsNotActivated(): static
    {
        $this->{$this->getIsActiveColumn()} = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function activated(): bool
    {
        return !is_null($this->{$this->getIsActiveColumn()});
    }

    /**
     * @return string
     */
    public function getIsActiveColumn(): string
    {
        return defined(static::class . '::IS_ACTIVE') ? static::IS_ACTIVE : 'is_active';
    }

    /**
     * @return string
     */
    public function getQualifiedIsActiveColumn(): string
    {
        return $this->qualifyColumn($this->getIsActiveColumn());
    }
}
