<?php

namespace Framework\Renderer;

use Framework\Renderer\Exceptions\InvalidFilterException;

class FiltersCollection
{
    private array $filters = [
        'escape' => 'htmlspecialchars',
        'strip' => 'strip_tags',
        'trim' => 'trim',
        'ucfirst' => 'ucfirst',
        'lcfirst' => 'lcfirst',
        'title' => 'ucwords',
        'upper' => 'mb_strtoupper',
        'lower' => 'mb_strtolower',
    ];

    public function handle(mixed $value, string|array $filters): mixed
    {
        $filters = is_string($filters) ? $this->parseFiltersToArray($filters) : $filters;

        if (!empty($filters)) {
            $this->filterExistGuard($filters);
        }

        foreach ($filters as $filter) {
            $value = $this->filters[$filter]($value);
        }

        return $value;
    }

    protected function parseFiltersToArray(string $filters): array
    {
        return explode('|', $filters);
    }

    protected function filterExistGuard(array $filters)
    {
        foreach ($filters as $filter) {
            if (!array_key_exists($filter, $this->filters)) {
                throw new InvalidFilterException($filter);
            }
        }
    }
}
