<?php

namespace Orchestra\Model\Concerns;

use Orchestra\Support\Traits\QueryFilter;

trait Searchable
{
    use QueryFilter;

    /**
     * Get searchable attributes.
     *
     * @return array
     */
    public function getSearchableColumns(): array
    {
        return property_exists($this, 'searchable') ? $this->searchable : [];
    }
}
