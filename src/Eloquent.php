<?php namespace Orchestra\Model;

use Closure;
use Illuminate\Support\Fluent;
use Illuminate\Database\Eloquent\Model;

abstract class Eloquent extends Model
{
    /**
     * Determine if the model instance uses soft deletes.
     *
     * @return bool
     */
    public function isSoftDeleting()
    {
        return (property_exists($this, 'forceDeleting') && $this->forceDeleting === false);
    }

    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function transaction(Closure $callback)
    {
        return $this->getConnection()->transaction($callback);
    }

    /**
     * Transform each attribute in the model using a callback.
     *
     * @param  callable  $callback
     *
     * @return \Illuminate\Support\Fluent
     */
    public function transform(callable $callback)
    {
        return new Fluent($callback($this));
    }
}
