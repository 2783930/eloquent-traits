<?php

namespace EloquentTraits\Publishable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|\Illuminate\Database\Query\Builder withUnpublished(bool $withPublished = true)
 */
trait Publishable
{
    #region Boot

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

    #endregion

    #region Helpers

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

    #endregion

    #region Virtual Attributes

    public function getIsPublishedAttribute(): bool
    {
        return !is_null($this->{$this->getPublishedAtColumn()});
    }

    #endregion

    #region Scopes

    public function scopePublished(Builder $builder): Builder
    {
        return $builder->whereNotNull($this->getPublishedAtColumn());
    }

    public function scopeNotPublished(Builder $builder): Builder
    {
        return $builder->whereNull($this->getPublishedAtColumn())
                       ->orWhere($this->getPublishedAtColumn(), '>', Carbon::now());
    }

    #endregion
}
