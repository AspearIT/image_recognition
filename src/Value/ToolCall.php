<?php

namespace Imreg\Value;

readonly class ToolCall
{
    /**
     * @param string $name
     * @param string[] $arguments [$argName => $argValue]
     */
    public function __construct(
        public string $name,
        public array $arguments,
    ) {
    }
}