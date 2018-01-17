<?php

namespace Orchestra\Model\Traits;

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
        return factory(static::class);
    }
}
