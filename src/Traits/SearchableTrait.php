<?php namespace Orchestra\Model\Traits;

use Orchestra\Support\Traits\QueryFilterTrait;

trait SearchableTrait
{
    use QueryFilterTrait;

    /**
     * Get searchable attributes.
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchable;
    }
}
