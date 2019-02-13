<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

trait Faker
{
    /**
     * Initiate faker factory.
     *
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    public static function faker(): FactoryBuilder
    {
        return \app(EloquentFactory::class)->of(static::class);
    }
}
