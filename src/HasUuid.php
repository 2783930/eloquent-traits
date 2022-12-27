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
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * @return void
     */
    public function initializeHasUuid(): void
    {
        $this->casts['uuid'] = 'string';
    }
}
