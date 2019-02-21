<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
        ['basic' => $basic, 'advanced' => $advanced] = $this->resolveSearchKeywords($search ?? '');
        $rules = $this->getSearchableRules();
        $others = [];

        foreach ($rules as $keyword => $callback) {
            if (Str::contains($keyword, ':*') || Str::contains($keyword, ':[]')) {
                [$tag, $type] = \explode(':', $keyword, 2);

                $results = Arr::where($advanced, function ($value) use ($tag) {
                    return Str::startsWith($value, "{$tag}:");
                });

                $query->unless(empty($results), function ($query) use ($callback, $results, $type) {
                    if ($type === '*') {
                        [, $value] = \explode(':', $results[0] ?? null, 2);
                        $value = \trim($value, '"');
                    } else {
                        $value = \array_map(function ($text) {
                            [, $value] = \explode(':', $text, 2);

                            return \trim($value, '"');
                        }, $results);
                    }

                    \call_user_func($callback, $query, $value);

                    return $query;
                });
            } else {
                $query->when(\in_array($keyword, $advanced), function ($query) use ($callback) {
                    \call_user_func($callback, $query);

                    return $query;
                });
            }
        }

        if (\is_null($columns) && \method_exists($this, 'getSearchableColumns')) {
            $columns = $this->getSearchableColumns();
        }

        return $this->setupWildcardQueryFilter($query, $basic, $columns ?? []);
    }

    /**
     * Resolve search keywords.
     *
     * @param  string  $keyword
     *
     * @return array
     */
    protected function resolveSearchKeywords(string $keyword): array
    {
        $basic = [];
        $advanced = [];

        $tags = \array_map(function ($value) {
            [$tag, ] = \explode(':', $value, 2);

            return "{$tag}:";
        }, \array_keys($this->getSearchableRules()));

        if (\preg_match_all('/([\w]+:\"[\w\s]*\"|[\w]+:[\w\S]+|[\w\S]+)\s?/', $keyword, $keywords)) {
            foreach ($keywords[1] as $index => $keyword) {
                if (! Str::startsWith($keyword, $tags)) {
                    \array_push($basic, $keyword);
                } else {
                    \array_push($advanced, $keyword);
                }
            }
        }

        return [
            'basic' => \implode(' ', $basic),
            'advanced' => $advanced,
        ];
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
