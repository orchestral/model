<?php namespace Orchestra\Model\Observer;

use Orchestra\Model\Role as Eloquent;
use Orchestra\Contracts\Authorization\Factory;

class Role
{
    /**
     * The authorization factory implementation.
     *
     * @var \Orchestra\Contracts\Authorization\Factory
     */
    protected $acl;

    /**
     * Construct a new role observer.
     *
     * @param  \Orchestra\Contracts\Authorization\Factory  $acl
     */
    public function __construct(Factory $acl)
    {
        $this->acl = $acl;
    }

    /**
     * On creating observer.
     *
     * @param  \Orchestra\Model\Role  $model
     *
     * @return void
     */
    public function creating(Eloquent $model)
    {
        $this->acl->addRole($model->getAttribute('name'));
    }

    /**
     * On deleting observer.
     *
     * @param  \Orchestra\Model\Role  $model
     *
     * @return void
     */
    public function deleting(Eloquent $model)
    {
        $this->acl->removeRole($model->getAttribute('name'));
    }

    /**
     * On updating/restoring observer.
     *
     * @param  \Orchestra\Model\Role  $model
     *
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
            $this->acl->addRole($currentName);
        } else {
            $this->acl->renameRole($originalName, $currentName);
        }
    }
}
