<?php namespace Orchestra\Model\Observer;

use Orchestra\Model\Role as Eloquent;
use Orchestra\Support\Facades\ACL;

class Role
{
    /**
     * On creating observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function creating(Eloquent $model)
    {
        ACL::addRole($model->getAttribute('name'));
    }

    /**
     * On deleting observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function deleting(Eloquent $model)
    {
        ACL::removeRole($model->getAttribute('name'));
    }

    /**
     * On updating/restoring observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function updating(Eloquent $model)
    {
        $originalName = $model->getOriginal('name');
        $currentName  = $model->getAttribute('name');
        $deletedAt    = null;

        if ($model->isSoftDeleting()) {
            $deletedAt = $model->getDeletedAtColumn();
        }

        $isRestoring = function ($model, $deletedAt) {
            return (! is_null($deletedAt)
                && is_null($model->getAttribute($deletedAt))
                && ! is_null($model->getOriginal($deletedAt)));
        };

        if ($isRestoring($model, $deletedAt)) {
            ACL::addRole($currentName);
        } else {
            ACL::renameRole($originalName, $currentName);
        }
    }
}
