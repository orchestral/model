<?php namespace Orchestra\Model\Traits;

use Illuminate\Database\Eloquent\Model;

trait UserOwnsTrait
{
    /**
     * Check if User model actually owns the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $related
     * @param  string  $key
     *
     * @return bool
     */
    public function owns(Model $related, $key = 'user_id')
    {
        return $relation->getAttribute($key) == $this->getKey();
    }
}
