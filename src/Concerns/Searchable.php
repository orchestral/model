<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravie\QueryFilter\Taxonomy;

trait Searchable
{
    /**
     * Advanced search from query builder.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string|null  $searchTerm
     * @param  array|null  $columns
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm, ?array $columns = null): Builder
    {
        if (\is_null($columns) && \method_exists($this, 'getSearchableColumns')) {
            $columns = $this->getSearchableColumns();
        }

        return (new Taxonomy(
            $searchTerm, $this->getSearchableRules(), $columns
        ))->apply($query);
    }

    /**
     * Get searchable rules.
     *
     * @return array
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
