<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laravie\QueryFilter\MatchQuery;

trait AdvancedSearchable
{
    use Searchable;

    /**
     * Advanced search from query builder.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string|null  $search
     * @param  array|null  $columns
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdvancedSearch(Builder $query, ?string $search, ?array $columns = null): Builder
    {
        if (\is_null($columns) && \method_exists($this, 'getSearchableColumns')) {
            $columns = $this->getSearchableColumns();
        }

        return (new MatchQuery(
            $search, $this->getSearchableRules(), $columns
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
}
