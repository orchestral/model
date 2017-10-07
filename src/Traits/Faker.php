<?php

namespace Orchestra\Model\Traits;

trait Faker
{
    /**
     * Initiate faker factory.
     *
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    public static function faker()
    {
        return factory(static::class);
    }
}
