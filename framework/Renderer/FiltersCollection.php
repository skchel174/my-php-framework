<?php

namespace Framework\Renderer;

use Framework\Renderer\Exceptions\InvalidFilterException;
use Framework\Renderer\Exceptions\InvalidFilterParametersException;
use Framework\Renderer\Exceptions\UnknownFilterRegisterException;

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
        $filters = is_string($filters) ? explode('|', $filters) : $filters;

        if (!empty($filters)) {
            $this->filterExistGuard($filters);
        }

        foreach ($filters as $filter) {
            $value = $this->filters[$filter]($value);
        }

        return $value;
    }

    public function register(string $alias, string $filter): void
    {
        if (!function_exists($filter)) {
            throw new UnknownFilterRegisterException($filter);
        }

        $reflection = new \ReflectionFunction($filter);
        if ($reflection->getNumberOfParameters() < 1 || $reflection->getNumberOfRequiredParameters() > 1) {
            throw new InvalidFilterParametersException($filter);
        }

        $this->filters[$alias] = $filter;
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
