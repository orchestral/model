<?php namespace Orchestra\Model\Observer;

use Orchestra\Support\Keyword;
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
     * On creating observer.
     *
     * @param  \Orchestra\Model\Role  $model
     *
     * @return void
     */
    public function saving(Eloquent $model)
    {
        if ((new Keyword($model->getAttribute('name')))->hasIn(['guest'])) {
            return false;
        }
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
        $original = $model->getOriginal('name');
        $current  = $model->getAttribute('name');

        if ($this->isRestoringModel($model)) {
            $this->acl->addRole($current);
        } else {
            $this->acl->renameRole($original, $current);
        }
    }

    /**
     * Is restoring model.
     *
     * @param  \Orchestra\Model\Role  $model
     * @param  string|null  $deleted
     *
     * @return bool
     */
    protected function isRestoringModel(Eloquent $model, $deleted = null)
    {
        if (! $model->isSoftDeleting()) {
            return false;
        }

        $deleted = $model->getDeletedAtColumn();

        return is_null($model->getAttribute($deleted)) && ! is_null($model->getOriginal($deleted));
    }
}
