<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Owns
{
    /**
     * Scope query to get model which related model actually owns the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $related
     * @param  string|null  $foreignKey
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwns(Builder $query, Model $related, ?string $foreignKey = null): Builder
    {
        if (\is_null($foreignKey)) {
            $foreignKey = $this->getForeignKey();
        }

        return $query->where(
            $this->getKeyName(), $related->getAttribute($foreignKey)
        );
    }

    /**
     * Check if related model actually owns the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $related
     * @param  string|null  $foreignKey
     *
     * @return bool
     */
    public function owns(Model $related = null, ?string $foreignKey = null): bool
    {
        if (\is_null($related)) {
            return false;
        }

        if (\is_null($foreignKey)) {
            $foreignKey = $this->getForeignKey();
        }

        return $related->getAttribute($foreignKey) == $this->getKey();
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    abstract public function getAttribute($key);

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    abstract public function getForeignKey();

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    abstract public function getKey();

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    abstract public function getKeyName();
}
