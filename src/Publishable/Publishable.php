<?php

namespace EloquentTraits\Publishable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static Builder|\Illuminate\Database\Query\Builder withUnpublished(bool $withUnpublished = true)
 * @method static Builder|\Illuminate\Database\Query\Builder onlyUnpublished()
 */
trait Publishable
{
    public static function bootPublishable(): void
    {
        static::addGlobalScope(new PublishingScope);
    }

    public function initializePublishable(): void
    {
        if (!isset($this->casts[$this->getPublishedAtColumn()])) {
            $this->casts[$this->getPublishedAtColumn()] = 'datetime';
        }

        if (!isset($this->fillable[$this->getPublishedAtColumn()])) {
            $this->fillable[] = $this->getPublishedAtColumn();
        }
    }

    public function publish(): void
    {
        $this->{$this->getPublishedAtColumn()} = Carbon::now();
        $this->save();
    }

    public function unPublish(): void
    {
        $this->{$this->getPublishedAtColumn()} = null;
        $this->save();
    }

    public function published(): bool
    {
        return !is_null($this->{$this->getPublishedAtColumn()});
    }

    public function getPublishedAtColumn(): string
    {
        return defined(static::class . '::PUBLISHED_AT') ? static::PUBLISHED_AT : 'published_at';
    }

    public function getQualifiedPublishedAtColumn(): string
    {
        return $this->qualifyColumn($this->getPublishedAtColumn());
    }

    public function getIsPublishedAttribute(): bool
    {
        return (bool)$this->{$this->getPublishedAtColumn()}?->isFuture();
    }
}
