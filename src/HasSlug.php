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
                'source' => property_exists($this, 'slug_column') ? $this->slug_column : 'title'
            ]
        ];
    }
}
