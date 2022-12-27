<?php

namespace EloquentTraits\HasStickyColumn;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder orderBySticky(bool $withoutSticky = false)
 * @method static Builder|\Illuminate\Database\Query\Builder withoutSticky()
 * @method static Builder|\Illuminate\Database\Query\Builder onlySticky()
 */
trait HasStickyColumn
{
    #region Boot

    public static function bootHasStickyColumn(): void
    {
        static::addGlobalScope(new StickyScope);
    }

    public function initializeHasStickyColumn(): void
    {
        if (!isset($this->casts[$this->getIsStickyColumn()])) {
            $this->casts[$this->getIsStickyColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsStickyColumn()])) {
            $this->fillable[] = $this->getIsStickyColumn();
        }
    }

    #endregion

    #region Helpers

    public function markAsSticky(): void
    {
        $this->{$this->getIsStickyColumn()} = true;
        $this->save();
    }

    public function markAsNotSticky(): void
    {
        $this->{$this->getIsStickyColumn()} = false;
        $this->save();
    }

    public function getIsStickyColumn(): string
    {
        /** @noinspection PhpUndefinedClassConstantInspection */
        return defined(static::class . '::IS_STICKY') ? static::IS_STICKY : 'is_sticky';
    }

    public function getQualifiedIsStickyColumn(): string
    {
        return $this->qualifyColumn($this->getIsStickyColumn());
    }

    #endregion
}
