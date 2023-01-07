<?php

namespace EloquentTraits\HasFirst;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder onlyFirst()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyNotFirst()
 */
trait HasFirst
{
    /**
     * @return void
     */
    public static function bootHasFirstFlag(): void
    {
        static::addGlobalScope(new HasFirstScope);
    }

    /**
     * @return void
     */
    public function initializeHasFirstFlag(): void
    {
        if (!isset($this->casts[$this->getIsFirstColumn()])) {
            $this->casts[$this->getIsFirstColumn()] = 'boolean';
        }
    }

    /**
     * @return string
     */
    public function getIsFirstColumn(): string
    {
        return defined(static::class . '::IS_FIRST') ? static::IS_FIRST : 'is_first';
    }

    /**
     * @return string
     */
    public function getQualifiedIsFirstColumn(): string
    {
        return $this->qualifyColumn($this->getIsFirstColumn());
    }
}
