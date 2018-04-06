<?php

namespace Orchestra\Model;

use Illuminate\Support\Collection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchestra\Contracts\Authorization\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class User extends Eloquent implements Authorizable, UserContract
{
    use Authenticatable,
        Concerns\CheckRoles,
        Concerns\Searchable,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Available user status as constant.
     */
    const UNVERIFIED = 0;
    const SUSPENDED = 63;
    const VERIFIED = 1;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * List of searchable attributes.
     *
     * @var array
     */
    protected $searchable = ['email', 'fullname'];

    /**
     * Has many and belongs to relationship with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Search user based on keyword as roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $keyword
     * @param  array  $roles
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $keyword = '', array $roles = []): Builder
    {
        $query->with('roles')->whereNotNull('users.id');

        if (! empty($roles)) {
            $query->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('roles.id', $roles);
            });
        }

        return $this->setupWildcardQueryFilter($query, $keyword, $this->getSearchableColumns());
    }

    /**
     * Set `password` mutator.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        if (Hash::needsRehash($value)) {
            $value = Hash::make($value);
        }

        $this->attributes['password'] = $value;
    }

    /**
     * Activate current user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->setAttribute('status', self::VERIFIED);

        return $this;
    }

    /**
     * Deactivate current user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->setAttribute('status', self::UNVERIFIED);

        return $this;
    }

    /**
     * Suspend current user.
     *
     * @return $this
     */
    public function suspend()
    {
        $this->setAttribute('status', self::SUSPENDED);

        return $this;
    }

    /**
     * Determine if the current user account activated or not.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->getAttribute('status') == self::VERIFIED;
    }

    /**
     * Determine if the current user account suspended or not.
     *
     * @return bool
     */
    public function isSuspended(): bool
    {
        return $this->getAttribute('status') == self::SUSPENDED;
    }

    /**
     * Assign role to user.
     *
     * @param  \Orchestra\Model\Role|int|array  $roles
     *
     * @return $this
     */
    public function attachRole($roles)
    {
        return $this->attachRoles($roles);
    }

    /**
     * Un-assign role from user.
     *
     * @param  \Orchestra\Model\Role|int|array  $roles
     *
     * @return $this
     */
    public function detachRole($roles)
    {
        return $this->detachRoles($roles);
    }

    /**
     * Get roles name as an array.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRoles(): Collection
    {
        // If the relationship is already loaded, avoid re-querying the
        // database and instead fetch the collection.
        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }

        return $this->getRelation('roles')->pluck('name');
    }
}
