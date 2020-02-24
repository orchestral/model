<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Model\HS;

trait Swappable
{
    /**
     * Make Hot-swappable model.
     */
    public static function hs(array $attributes = []): Model
    {
        return HS::make(static::hsAliasName(), $attributes);
    }

    /**
     * Make Hot-swappable model.
     *
     * @param  array  $attributes
     */
    public static function hsQuery(): Builder
    {
        return static::hs()->newQuery();
    }

    /**
     * Make Hot-swappable faker model.
     *
     * @param  array  $attributes
     */
    public static function hsFaker(): FactoryBuilder
    {
        $arguments = \func_get_args();

        \array_unshift($arguments, static::hsFinder());

        return \factory(...$arguments);
    }

    /**
     * Make Hot-swappable model on write connection.
     */
    public static function hsOnWriteConnection(): Builder
    {
        return static::hs()->query()->onWritePdo();
    }

    /**
     * Make Hot-swappable model on specific connection.
     */
    public static function hsOn(?string $connection = null): Builder
    {
        return \tap(static::hs(), static function ($instance) use ($connection) {
            $instance->setConnection($connection);
        })->newQuery();
    }

    /**
     * Find Hot-swappable full namespace model.
     */
    public static function hsFinder(): string
    {
        return HS::eloquent(static::hsAliasName());
    }

    /**
     * Get Hot-swappable alias name.
     */
    abstract public static function hsAliasName(): string;
}
