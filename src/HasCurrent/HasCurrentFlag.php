<?php

namespace EloquentTraits\HasCurrent;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder onlyCurrent()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyNotCurrent()
 */
trait HasCurrentFlag
{
    /**
     * @return void
     */
    public static function bootHasCurrentFlag(): void
    {
        static::addGlobalScope(new HasCurrentScope);
    }

    /**
     * @return void
     */
    public function initializeHasCurrentFlag(): void
    {
        if (!isset($this->casts[$this->getIsCurrentColumn()])) {
            $this->casts[$this->getIsCurrentColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsCurrentColumn()])) {
            $this->fillable[] = $this->getIsCurrentColumn();
        }
    }

    /**
     * @return string
     */
    public function getIsCurrentColumn(): string
    {
        return defined(static::class . '::IS_CURRENT') ? static::IS_CURRENT : 'is_current';
    }

    /**
     * @return string
     */
    public function getQualifiedIsCurrentColumn(): string
    {
        return $this->qualifyColumn($this->getIsCurrentColumn());
    }
}
