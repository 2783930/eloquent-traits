<?php

namespace EloquentTraits\HasSelectable;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
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
        static::addGlobalScope(new HasSelectableScope);
    }

    /**
     * @return void
     */
    public function initializeHasSelectableColumn(): void
    {
        if (!isset($this->casts[$this->getIsSelectableColumn()])) {
            $this->casts[$this->getIsSelectableColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsSelectableColumn()])) {
            $this->fillable[] = $this->getIsSelectableColumn();
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
