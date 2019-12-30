<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravie\QueryFilter\Taxonomy;

trait Searchable
{
    /**
     * Search based on keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null  $searchTerm
     * @param array|null  $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm, ?array $columns = null): Builder
    {
        return (new Taxonomy(
            $searchTerm, $this->getSearchableTerms(), $columns ?? $this->getSearchableColumns()
        ))->apply($query);
    }

    /**
     * Get searchable rules.
     *
     * @return array
     */
    public function getSearchableTerms(): array
    {
        return $this->getSearchableRules();
    }

    /**
     * Get searchable rules.
     *
     * @return array
     *
     * @deprecated v4.3.0
     */
    public function getSearchableRules(): array
    {
        return [];
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
