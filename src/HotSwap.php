<?php

namespace Orchestra\Model;

use RuntimeException;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class HotSwap
{
    /**
     * List of swappable models.
     *
     * @var array
     */
    protected static $swappable = [
        'Role' => Role::class,
        'User' => User::class,
    ];

    /**
     * Register swappable model.
     *
     * @param  string  $alias
     * @param  string  $className
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function register(string $alias, string $className): void
    {
        static::validateClassIsEloquentModel($className);

        if (\array_key_exists($alias, static::$swappable)) {
            throw new RuntimeException("{$alias} has been registered, please use override to make a hot-swap to {$className}.");
        }

        static::$swappable[$alias] = $className;
    }

    /**
     * Override swappable model.
     *
     * @param  string  $alias
     * @param  string  $className
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function override(string $alias, string $className): void
    {
        static::validateClassIsEloquentModel($className);

        static::$swappable[$alias] = $className;
    }

    /**
     * Resolve model class name.
     *
     * @param  string $alias
     *
     * @return string
     */
    public static function model(string $alias): string
    {
        if (\array_key_exists($alias, static::$swappable)) {
            return static::$swappable[$alias];
        }

        return $alias;
    }

    /**
     * Make a model instance.
     *
     * @param  string  $alias
     * @param  array  $attributes
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function make(string $alias, array $attributes = []): Model
    {
        $className = static::model($alias);

        static::validateClassIsEloquentModel($className);

        return new $className($attributes);
    }

    /**
     * Validate class is an eloquent model.
     *
     * @param  string  $className
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected static function validateClassIsEloquentModel(string $className): void
    {
        if (! \is_subclass_of($className, Model::class)) {
            throw new InvalidArgumentException("Given {$className} is not a subclass of ".Model::class);
        }
    }
}
