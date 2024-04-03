<?php

namespace Imreg\Value;

readonly class Message
{
    public function __construct(
        public string $role,
        public string $content,
    ) {}
}