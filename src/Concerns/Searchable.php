<?php

namespace Orchestra\Model\Concerns;

use Orchestra\Support\Traits\QueryFilter;

trait Searchable
{
    use QueryFilter;

    /**
     * Search based on keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null. $keyword
     * @param array|null  $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $keyword, ?array $columns = null): Builder
    {
        return $this->setupWildcardQueryFilter($query, $keyword ?? '', $columns ?? $this->getSearchableColumns());
    }

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
