<?php

namespace EloquentTraits\HasSelectable;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder onlySelectable()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyNotSelectable()
 */
trait HasSelectableColumn
{
    /**
     * @return void
     */
    public static function bootHasSelectableColumn(): void
    {
        static::addGlobalScope(new SelectableScope);
    }

    /**
     * @return void
     */
    public function initializeHasSelectableColumn(): void
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
    public function getIsSelectableColumn(): string
    {
        return defined(static::class . '::IS_SELECTABLE') ? static::IS_SELECTABLE : 'is_selectable';
    }

    /**
     * @return string
     */
    public function getQualifiedIsSelectableColumn(): string
    {
        return $this->qualifyColumn($this->getIsSelectableColumn());
    }
}
