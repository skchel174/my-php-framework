<?php

namespace Framework\Command;

class Output
{
    private array $tags = [
        '<br>' => "\n",

        '<h-tab>' => "\t",
        '<v-tab>' => "\v",

        '<f-red>' => "\033[31m",
        '</f-red>' => "\033[m",

        '<f-green>' => "\033[32m",
        '</f-green>' => "\033[m",

        '<f-yellow>' => "\033[33m",
        '</f-yellow>' => "\033[m",

        '<f-blue>' => "\033[34m",
        '</f-blue>' => "\033[m",

        '<bg-red>' => "\033[41m",
        '</bg-red>' => "\033[m",

        '<bg-green>' => "\033[42m",
        '</bg-green>' => "\033[m",

        '<bg-yellow>' => "\033[43m",
        '</bg-yellow>' => "\033[m",

        '<bg-blue>' => "\033[44m",
        '</bg-blue>' => "\033[m",
    ];

    private string $output;

    public function __construct(string $output)
    {
        $this->output = $output;
    }

    public function print(): void
    {
        echo strtr($this->output, $this->tags) . PHP_EOL;
    }
}
