<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;

class UserMeta extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'value',
    );

    /**
     * Belongs to relationship with User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('\Orchestra\Model\User', 'user_id');
    }

    /**
     * Return a meta data belong to a user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @param  string                                   $name
     * @param  integer                                  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $name, $userId)
    {
        return $query->where('user_id', '=', $userId)
            ->where('name', '=', $name);
    }
}
