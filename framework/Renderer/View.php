<?php

namespace Framework\Renderer;

/**
 * @method static block(string $string)
 * @method static close()
 * @method static extends (string $string)
 * @method static include (string $string, array $parameters = [])
 * @method static get(mixed $parameter, array|string $filters = [])
 * @method static assets(string $string)
 * @method static route(string $string)
 * @method static csrf()
 * @method static method(string $string)
 */
class View
{
    private static Template $template;

    public static function init(Template $template)
    {
        static::$template = $template;
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return static::$template->$name(...$arguments);
    }

    private function __construct() {}
    private function __clone() {}
}
