<?php

namespace Orchestra\Model\Concerns;

use Orchestra\Model\HS;
use Illuminate\Database\Eloquent\Model;

trait Swappable
{
    /**
     * Make swappable model using hsAliasName.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function hs(array $attributes = []): Model
    {
        return HS::make(static::hsAliasName(), $attributes);
    }

    /**
     * Make swappable model using hsAliasName on write connection.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function hsOnWriteConnection(): Builder
    {
        return HS::make(static::hsAliasName(), [])->query()->onWritePdo();
    }

    /**
     * Make swappable model using hsAliasName on connection.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function hsOn(?string $connection = null): Builder
    {
        return \tap(HS::make(static::hsAliasName(), []), static function ($instance) use ($connection) {
            $instance->setConnection($connection);
        })->newQuery();
    }

    /**
     * Get Hot-swappable alias name.
     *
     * @return string
     */
    abstract public static function hsAliasName(): string;
}
