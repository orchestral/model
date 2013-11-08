<?php namespace Orchestra\Model\Observer;

use Orchestra\Support\Facades\Acl;
use Orchestra\Model\Role;

class Role
{
    /**
     * On creating observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function creating(Role $model)
    {
        Acl::addRole($model->getAttribute('name'));
    }

    /**
     * On deleting observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function deleting(Role $model)
    {
        Acl::removeRole($model->getAttribute('name'));
    }

    /**
     * On updating/restoring observer.
     *
     * @param  \Orchestra\Model\Role    $model
     * @return void
     */
    public function updating(Role $model)
    {
        $originalName = $model->getOriginal('name');
        $currentName  = $model->getAttribute('name');
        $deletedAt    = $model->getDeletedAtColumn();

        $isRestoring = function ($model, $deletedAt) {
            return ($model->isSoftDeleting()
                and is_null($model->getAttribute($deletedAt))
                and ! is_null($model->getOriginal($deletedAt)));
        };

        if ($isRestoring($model, $deletedAt)) {
            Acl::addRole($currentName);
        } else {
            Acl::renameRole($originalName, $currentName);
        }
    }
}
