<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Database\Eloquent\Builder;

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
        return $this->scopeSearch($query, $search, $columns);
    }
}
