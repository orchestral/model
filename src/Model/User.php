<?php namespace Orchestra\Model;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Hash;
use Orchestra\Notifier\NotifiableTrait;
use Illuminate\Database\Eloquent\Builder;
use Orchestra\Notifier\RecipientInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Orchestra\Support\Traits\QueryFilterTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface, RecipientInterface
{
    use NotifiableTrait, QueryFilterTrait, RemindableTrait, SoftDeletingTrait, UserTrait;

    /**
     * Available user status as constant.
     */
    const UNVERIFIED = 0;
    const SUSPENDED = 63;
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
    protected $hidden = array('password', 'remember_token');

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('deleted_at');

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
                $query->whereIn('roles.id', $roles);
            });
        }

        return $this->setupWildcardQueryFilter($query, $keyword, array('email', 'fullname'));
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

    /**
     * Activate current user
     *
     * @return \Orchestra\Model\User
     */
    public function activate()
    {
        $this->setAttribute('status', self::VERIFIED);

        return $this;
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
     * Deactivate current user
     *
     * @return \Orchestra\Model\User
     */
    public function deactivate()
    {
        $this->setAttribute('status', self::UNVERIFIED);

        return $this;
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
     * Determine if the current user account activated or not
     *
     * @return boolean
     */
    public function isActivated()
    {
        return ($this->getAttribute('status') == self::VERIFIED);
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
     * Determine if the current user account suspended or not
     *
     * @return boolean
     */
    public function isSuspended()
    {
        return ($this->getAttribute('status') == self::SUSPENDED);
    }

    /**
     * Send notification for a user.
     *
     * @param  string       $subject
     * @param  string|array $view
     * @param  array        $data
     * @return boolean
     */
    public function notify($subject, $view, array $data = array())
    {
        return $this->sendNotification($this, $subject, $view, $data);
    }


    /**
     * Suspend current user
     *
     * @return \Orchestra\Model\User
     */
    public function suspend()
    {
        $this->setAttribute('status', self::SUSPENDED);

        return $this;
    }
}
