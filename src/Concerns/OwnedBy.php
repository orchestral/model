<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait OwnedBy
{
    /**
     * Scope query to get model which are owned by the specified model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $related
     * @param  string|null  $key
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy(Builder $query, Model $related, ?string $key = null): Builder
    {
        if (\is_null($key)) {
            $key = $related->getForeignKey();
        }

        return $query->where($key, $related->getKey());
    }

    /**
     * Check if related model actually owns the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $related
     * @param  string|null  $key
     *
     * @return bool
     */
    public function ownedBy(Model $related = null, ?string $key = null): bool
    {
        if (\is_null($related)) {
            return false;
        }

        if (\is_null($key)) {
            $key = $related->getForeignKey();
        }

        return $this->getAttribute($key) == $related->getKey();
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    abstract public function getAttribute($key);
}
