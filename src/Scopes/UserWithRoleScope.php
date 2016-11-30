<?php

namespace Orchestra\Model\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class UserWithRoleScope implements Scope
{
    /**
     * The selected role.
     *
     * @var string
     */
    protected $role;

    /**
     * Construct the scope.
     *
     * @param  string  $role
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (empty($this->role)) {
            return;
        }

        $builder->whereHas('roles', function ($query) {
            $query->where('name', '=', $this->role);
        });
    }
}
