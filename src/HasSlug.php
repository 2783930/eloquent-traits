<?php

namespace EloquentTraits;

use Cviebrock\EloquentSluggable\Sluggable;

trait HasSlug
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => $this->getSlugColumn()
            ]
        ];
    }

    /**
     * @return string
     */
    public function getSlugColumn(): string
    {
        return defined(static::class . '::SLUG_SOURCE') ? static::SLUG_SOURCE : 'title';
    }
}
