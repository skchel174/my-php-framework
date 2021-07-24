<?php

namespace Framework\Renderer\Extensions;

class AssetsDispatcher
{
    const ASSETS_DIR = BASE_DIR . '/assets';

    public function assets(string $file): string
    {
        return static::ASSETS_DIR . '/' . $file;
    }
}
