<?php

namespace Orchestra\Model;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Orchestra\Contracts\Support\Transformable;
use Orchestra\Support\Fluent;

abstract class Eloquent extends Model implements Transformable
{
    use Concerns\Faker;

    /**
     * Get qualified column name.
     *
     * @param  string  $column
     *
     * @return string
     */
    public static function column(string $column): string
    {
        return new Expression(
            (new static())->qualifyColumn($column)
        );
    }

    /**
     * Determine if the model instance uses soft deletes.
     *
     * @return bool
     */
    public function isSoftDeleting(): bool
    {
        return \property_exists($this, 'forceDeleting') && $this->forceDeleting === false;
    }

    /**
     * Save the model to the database if exists.
     *
     * @param  array  $options
     *
     * @return bool
     */
    public function saveIfExists(array $options = []): bool
    {
        if ($this->exists === false) {
            return false;
        }

        return $this->save($options);
    }

    /**
     * Save the model to the database using transaction if exists.
     *
     * @param  array  $options
     *
     * @return bool
     */
    public function saveIfExistsOrFail(array $options = []): bool
    {
        if ($this->exists === false) {
            return false;
        }

        return $this->saveOrFail($options);
    }

    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public function usesTransaction(Closure $callback)
    {
        return $this->getConnection()->transaction($callback);
    }

    /**
     * Transform each attribute in the model using a callback.
     *
     * @param  callable  $callback
     *
     * @return \Orchestra\Support\Fluent
     */
    public function transform(callable $callback)
    {
        return new Fluent($callback($this));
    }
}
