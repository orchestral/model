<?php

namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class HS
{
    /**
     * List of swappable models.
     *
     * @var array
     */
    private static $swappable = [
        'Role' => Role::class,
        'User' => User::class,
    ];

    /**
     * Register swappable model.
     *
     * @param  string  $class
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function register(string $class): void
    {
        static::validateClassIsEloquentModel($class);
        static::validateClassIsSwappable($class);

        static::$swappable[$class::hsAliasName()] = $class;
    }

    /**
     * Override swappable model.
     *
     * @param  string  $alias
     * @param  string  $class
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function override(string $alias, string $class): void
    {
        static::validateClassIsEloquentModel($class);

        static::$swappable[$alias] = $class;
    }

    /**
     * Resolve model class name.
     *
     * @param  string  $alias
     *
     * @return string
     */
    public static function eloquent(string $alias): string
    {
        return \array_key_exists($alias, static::$swappable) ? static::$swappable[$alias] : $alias;
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
        $class = static::eloquent($alias);

        static::validateClassIsEloquentModel($class);

        return new $class($attributes);
    }

    /**
     * Flush hot-swap mapping.
     *
     * @return void
     */
    public static function flush(): void
    {
        static::$swappable = [
            'Role' => Role::class,
            'User' => User::class,
        ];
    }

    /**
     * Validate class is an eloquent model.
     *
     * @param  string  $class
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    private static function validateClassIsEloquentModel(string $class): void
    {
        if (! \is_subclass_of($class, Model::class)) {
            throw new InvalidArgumentException(\sprintf('Given [%s] is not a subclass of [%s].', $class, Model::class));
        }
    }

    /**
     * Validate class is an eloquent model.
     *
     * @param  string  $class
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    private static function validateClassIsSwappable(string $class): void
    {
        $uses = \class_uses_recursive($class);

        if (! isset($uses[Concerns\Swappable::class])) {
            throw new InvalidArgumentException(\sprintf("Given [%s] doesn't use [%s] trait.", $class, Concerns\Swappable::class));
        }
    }
}
