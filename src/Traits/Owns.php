<?php

namespace Orchestra\Model\Traits;

use Illuminate\Database\Eloquent\Model;

trait Owns
{
    /**
     * Check if related model actually owns the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $related
     * @param  string|null  $foreignKey
     *
     * @return bool
     */
    public function owns(Model $related = null, $foreignKey = null)
    {
        if (is_null($related)) {
            return false;
        }

        if (is_null($foreignKey)) {
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
}
