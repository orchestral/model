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
     * Make swappable model using hsAliasName.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function hsOnWriteConnection(): Builder
    {
        return HS::make(static::hsAliasName(), [])->query()->onWritePdo();
    }


    /**
     * Get Hot-swappable alias name.
     *
     * @return string
     */
    abstract public static function hsAliasName(): string;
}
