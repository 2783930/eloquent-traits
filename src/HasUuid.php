<?php

namespace EloquentTraits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * @return void
     */
    public static function bootHasUuid(): void
    {
        static::saving(function ($model) {
            if (empty($model->{$this->getUuidColumn()})) {
                $model->{$this->getUuidColumn()} = Str::uuid();
            }
        });
    }

    /**
     * @return void
     */
    public function initializeHasUuid(): void
    {
        $this->casts[$this->getUuidColumn()] = 'string';
    }

    /**
     * @return string
     */
    public function getUuidColumn(): string
    {
        return defined(static::class . '::UUID') ? static::UUID : 'uuid';
    }
}
