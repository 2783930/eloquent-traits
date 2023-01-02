<?php

namespace EloquentTraits\HasStickyColumn;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder orderBySticky(string|false $direction = 'desc')
 * @method static Builder|\Illuminate\Database\Query\Builder withoutSticky()
 * @method static Builder|\Illuminate\Database\Query\Builder onlySticky()
 */
trait HasStickyColumn
{
    /**
     * @return void
     */
    public static function bootHasStickyColumn(): void
    {
        static::addGlobalScope(new StickyScope);
    }

    /**
     * @return void
     */
    public function initializeHasStickyColumn(): void
    {
        if (!isset($this->casts[$this->getIsStickyColumn()])) {
            $this->casts[$this->getIsStickyColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsStickyColumn()])) {
            $this->fillable[] = $this->getIsStickyColumn();
        }
    }
}
