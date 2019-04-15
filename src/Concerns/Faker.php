<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\FactoryBuilder;

trait Faker
{
    /**
     * Initiate faker factory.
     *
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    public static function faker(): FactoryBuilder
    {
        $arguments = \func_get_args();

        \array_unshift($arguments, static::class);

        return \factory(...$arguments);
    }
}
