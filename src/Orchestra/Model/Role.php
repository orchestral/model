<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

class Role extends Eloquent {

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
	protected $fillable = array(
		'name',
	);

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

	/**
	 * Default roles.
	 *
	 * @var array
	 */
	protected static $defaultRoles = array(
		'admin'  => 1,
		'member' => 2,
	);

	/**
	 * Set default roles.
	 * 
	 * @param  array    $roles
	 * @return void
	 */
	public static function setDefaultRoles(array $roles)
	{
		static::$defaultRoles = array_merge(static::$defaultRoles, $roles);
	}

	/**
	 * Has many and belongs to relationship with User.
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() 
	{
		return $this->belongsToMany('\Orchestra\Model\User', 'user_role')->withTimestamps();
	}

	/**
	 * Get default roles for Orchestra Platform
	 *
	 * @return self
	 */
	public static function admin()
	{
		return static::find(static::$defaultRoles['admin']);
	}

	/**
	 * Get default member roles for Orchestra Platform
	 *
	 * @return self
	 */
	public static function member()
	{
		return static::find(static::$defaultRoles['member']);
	}
}
