<?php namespace Orchestra\Model;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Orchestra\Notifier\RecipientInterface;
use Illuminate\Support\Facades\Hash;
use Orchestra\Support\Str;

class User extends Eloquent implements UserInterface, RemindableInterface, RecipientInterface
{
    /**
     * Available user status as constant.
     */
    const UNVERIFIED = 0;
    const VERIFIED   = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Indicates if the model should soft delete.
     *
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * Has many and belongs to relationship with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('\Orchestra\Model\Role', 'user_role')->withTimestamps();
    }

    /**
     * Search user based on keyword as roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @param  string                                   $keyword
     * @param  array                                    $roles
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $keyword = '', $roles = array())
    {
        $query->with('roles')->whereNotNull('users.id');

        if (! empty($roles)) {
            $query->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('id', $roles);
            });
        }

        if (! empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $keyword = Str::searchable($keyword);

                foreach ($keyword as $key) {
                    $query->orWhere('email', 'LIKE', $key)
                        ->orWhere('fullname', 'LIKE', $key);
                }
            });
        }

        return $query;
    }

    /**
     * Set `password` mutator.
     *
     * @param  string   $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * Get the e-mail address where notification are sent.
     *
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->getReminderEmail();
    }

    /**
     * Get the fullname where notification are sent.
     *
     * @return string
     */
    public function getRecipientName()
    {
        return $this->fullname;
    }

    /**
     * Assign role to user.
     *
     * @param  integer|array $roles
     * @return void
     */
    public function attachRole($roles)
    {
        $this->roles()->sync((array) $roles, false);
    }

    /**
     * Unassign role from user.
     *
     * @param  integer|array $roles
     * @return void
     */
    public function detachRole($roles)
    {
        $this->roles()->detach((array) $roles);
    }

     /**
     * Determine if current user has the given role.
     *
     * @param  string   $roles
     * @return boolean
     */
    public function is($roles)
    {
        $userRoles = $this->getRoles();

        // For a pre-caution, we should return false in events where user
        // roles not an array.
        if (! is_array($userRoles)) {
            return false;
        }

        // We should ensure that all given roles match the current user,
        // consider it as a AND condition instead of OR.
        foreach ((array) $roles as $role) {
            if (! in_array($role, $userRoles)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if current user has any of the given role.
     *
     * @param  array   $roles
     * @return boolean
     */
    public function isAny(array $roles)
    {
        $userRoles = $this->getRoles();

        // For a pre-caution, we should return false in events where user
        // roles not an array.
        if (! is_array($userRoles)) {
            return false;
        }

        // We should ensure that any given roles match the current user,
        // consider it as OR condition.
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if current user does not has any of the given role.
     *
     * @param  string   $roles
     * @return boolean
     */
    public function isNot($roles)
    {
        return ! $this->is($roles);
    }

    /**
     * Determine if current user does not has any of the given role.
     *
     * @param  array   $roles
     * @return boolean
     */
    public function isNotAny(array $roles)
    {
        return ! $this->isAny($roles);
    }

    /**
     * Get roles name as an array.
     *
     * @return array
     */
    public function getRoles()
    {
        // If the relationship is already loaded, avoid re-querying the
        // database and instead fetch the collection.
        $roles = (array_key_exists('roles', $this->relations) ? $this->relations['roles'] : $this->roles());

        return $roles->lists('name');
    }
}
