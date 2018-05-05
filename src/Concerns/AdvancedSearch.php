<?php

namespace Orchestra\Model\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait AdvancedSearch
{
    /**
     * Build search from query builder.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdvancedSearch(Builder $query, string $keyword): Builder
    {
        $keywords = explode(' ', $keyword);
        $rules = $this->advancedSearchableRules();

        foreach ($rules as $keyword => $callback) {
            if (Str::contains($keyword, ':*') || Str::contains($keyword, ':[]')) {
                [$tag, $type] = explode(':', $keyword, 2);

                $results = Arr::where($keywords, function ($value) use ($tag) {
                    return Str::startsWith($value, "{$tag}:");
                });

                $query->when(! empty($results), function ($query) use ($callback, $results, $type) {
                    if ($type === '*') {
                        [, $value] = explode(':', $results[0] ?? null, 2);
                    } else {
                        $value = array_map(function ($text) {
                            [, $value] = explode(':', $text, 2);

                            return $value;
                        }, $results);
                    }

                    call_user_func($callback, $query, $value);

                    return $query;
                });
            } else {
                $query->when(in_array($keyword, $keywords), function ($query) use ($callback) {
                    call_user_func($callback, $query);

                    return $query;
                });
            }
        }

        return $query;
    }

    /**
     * Rules definitions.
     *
     * @return array
     */
    abstract protected function advancedSearchableRules(): array;
}
