<?php

namespace Imreg\Value;

readonly class Tool
{
    public function __construct(
        public string $name,
        public string $description,
        public array $arguments,
    ) {}
}