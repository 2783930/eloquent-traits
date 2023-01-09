<?php

namespace EloquentTraits\HasVisibility;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder onlyVisible()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyHidden()
 */
trait HasVisibility
{

    /**
     * @return void
     */
    public static function bootHasVisibility(): void
    {
        static::addGlobalScope(new VisibilityScope);
    }

    /**
     * @return void
     */
    public function initializeHasVisibility(): void
    {
        if (!isset($this->casts[$this->getIsVisibleColumn()])) {
            $this->casts[$this->getIsVisibleColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsVisibleColumn()])) {
            $this->fillable[] = $this->getIsVisibleColumn();
        }
    }

    /**
     * @return string
     */
    public function getIsVisibleColumn(): string
    {
        return defined(static::class . '::IS_VISIBLE') ? static::IS_VISIBLE : 'is_visible';
    }

    /**
     * @return string
     */
    public function getQualifiedIsVisibleColumn(): string
    {
        return $this->qualifyColumn($this->getIsVisibleColumn());
    }
}
