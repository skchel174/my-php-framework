<?php

namespace Framework\Container;

class ConfigLoader
{
    const GLOBAL_PATTERN = '{*/*,*}.php';
    const LOCAL_PATTERN = '{*/*,*}.php.local';
    protected string $confDir;

    public function __construct(string $confDir)
    {
        $this->confDir = $confDir;
    }

    public function load(): array
    {
        $globalOptions = $this->mergeFiles(static::GLOBAL_PATTERN);
        $localOptions = $this->mergeFiles(static::LOCAL_PATTERN);
        return array_replace_recursive($globalOptions, $localOptions);
    }

    protected function mergeFiles($pattern): array
    {
        $config = array_map(function ($file) {
            return require $file;
        }, glob($this->confDir . '/' . $pattern, GLOB_BRACE));

        return array_merge(...$config);
    }
}
