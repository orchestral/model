<?php

namespace Orchestra\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Default roles.
     *
     * @var array
     */
    protected static $defaultRoles = [
        'admin'  => 1,
        'member' => 2,
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set default roles.
     *
     * @param  array  $roles
     *
     * @return void
     */
    public static function setDefaultRoles(array $roles)
    {
        static::$defaultRoles = array_merge(static::$defaultRoles, $roles);
    }

    /**
     * Has many and belongs to relationship with User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role')->withTimestamps();
    }

    /**
     * Get default roles for Orchestra Platform.
     *
     * @return $this|null
     */
    public static function admin()
    {
        return static::find(static::$defaultRoles['admin']);
    }

    /**
     * Get default member roles for Orchestra Platform.
     *
     * @return $this|null
     */
    public static function member()
    {
        return static::find(static::$defaultRoles['member']);
    }
}
