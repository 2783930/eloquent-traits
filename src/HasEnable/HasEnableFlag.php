<?php

namespace EloquentTraits\HasEnable;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder onlyEnabled()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyDisabled()
 */
trait HasEnableFlag
{
    /**
     * @return void
     */
    public static function bootHasEnableFlag(): void
    {
        static::addGlobalScope(new HasEnableScope);
    }

    /**
     * @return void
     */
    public function initializeHasEnableFlag(): void
    {
        if (!isset($this->casts[$this->getIsEnableColumn()])) {
            $this->casts[$this->getIsEnableColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsEnableColumn()])) {
            $this->fillable[] = $this->getIsEnableColumn();
        }
    }

    /**
     * @return string
     */
    public function getIsEnableColumn(): string
    {
        return defined(static::class . '::IS_ENABLE') ? static::IS_ENABLE : 'is_enable';
    }

    /**
     * @return string
     */
    public function getQualifiedIsEnableColumn(): string
    {
        return $this->qualifyColumn($this->getIsEnableColumn());
    }
}
