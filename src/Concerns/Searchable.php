<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravie\QueryFilter\SearchQuery;

trait Searchable
{
    /**
     * Search based on keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null  $keyword
     * @param array|null  $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $keyword, ?array $columns = null): Builder
    {
        return (new SearchQuery(
            $keyword ?? '', $columns ?? $this->getSearchableColumns()
        ))->apply($query);
    }

    /**
     * Get searchable attributes.
     *
     * @return array
     */
    public function getSearchableColumns(): array
    {
        return \property_exists($this, 'searchable') ? $this->searchable : [];
    }
}
