<?php

namespace EloquentTraits\HasDefault;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder onlyDefault()
 * @method static Builder|\Illuminate\Database\Query\Builder onlyNotDefault()
 */
trait HasDefaultFlag
{
    /**
     * @return void
     */
    public static function bootHasDefaultFlag(): void
    {
        static::addGlobalScope(new HasDefaultScope);
    }

    /**
     * @return void
     */
    public function initializeHasDefaultFlag(): void
    {
        if (!isset($this->casts[$this->getIsDefaultColumn()])) {
            $this->casts[$this->getIsDefaultColumn()] = 'boolean';
        }

        if (!isset($this->fillable[$this->getIsDefaultColumn()])) {
            $this->fillable[] = $this->getIsDefaultColumn();
        }
    }

    /**
     * @return string
     */
    public function getIsDefaultColumn(): string
    {
        return defined(static::class . '::IS_DEFAULT') ? static::IS_DEFAULT : 'is_defaul';
    }

    /**
     * @return string
     */
    public function getQualifiedIsDefaultColumn(): string
    {
        return $this->qualifyColumn($this->getIsDefaultColumn());
    }
}
