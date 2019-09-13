<?php

namespace Orchestra\Model\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserWithRoleScope implements Scope
{
    /**
     * The selected role.
     *
     * @var string|array
     */
    protected $roles;

    /**
     * Construct the scope.
     *
     * @param  string|array  $roles
     */
    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $roles = (array) $this->roles;

        if (empty($roles)) {
            return;
        }

        $builder->whereHas('roles', static function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        });
    }
}
