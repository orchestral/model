<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model;

abstract class Eloquent extends Model
{
    /**
     * Determine if the model instance uses soft deletes.
     *
     * @return bool
     */
    public function isSoftDeleting()
    {
        return (property_exists($this, 'forceDeleting') && $this->forceDeleting === false);
    }
}
