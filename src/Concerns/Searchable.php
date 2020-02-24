<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravie\QueryFilter\Taxonomy;

trait Searchable
{
    /**
     * Advanced search from query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm, ?array $columns = null): Builder
    {
        return (new Taxonomy(
            $searchTerm, $this->getSearchableTerms(), $columns ?? $this->getSearchableColumns()
        ))->apply($query);
    }

    /**
     * Get searchable rules.
     */
    public function getSearchableTerms(): array
    {
        return [];
    }

    /**
     * Get searchable attributes.
     */
    public function getSearchableColumns(): array
    {
        return \property_exists($this, 'searchable') ? $this->searchable : [];
    }
}
