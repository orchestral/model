<?php

namespace Orchestra\Model\Traits;

use Orchestra\Support\Traits\QueryFilter;

trait Searchable
{
    use QueryFilter;

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
