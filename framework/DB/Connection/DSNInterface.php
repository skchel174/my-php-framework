<?php

namespace Framework\DB\Connection;

interface DSNInterface
{
    public function getParams(): array;
    public function __toString(): string;
}
