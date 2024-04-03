<?php

namespace Imreg\Value;

readonly class Image
{
    public function __construct(
        public string $type,
        public string $path,
    ) {}

    public function content(): string
    {
        return base64_encode(file_get_contents($this->path));
    }

    public function storePermanently(string $name): self
    {
        $destination = __DIR__ . '/../../images/' . $name;
        if ($this->path === $destination) {
            return $this;
        }
        file_put_contents($destination, file_get_contents($this->path));
        return new self($this->type, $destination);
    }
}