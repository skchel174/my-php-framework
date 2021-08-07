<?php

namespace Framework\Renderer\Extensions;

class AssetsPathExtension
{
    const ASSETS_DIR = 'assets';

    public function assets(string $file): string
    {
        return static::ASSETS_DIR . '/' . $file;
    }
}
