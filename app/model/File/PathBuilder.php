<?php
declare(strict_types=1);

namespace App\Model\File;

use App\Model\Exception\Logic\InvalidArgumentException;

final class PathBuilder
{

    private string $root;

    private string $path;

    /** @var array<int, string> */
    private array $prefix;

    /** @var array<int, string> */
    private array $suffix;

    public function __construct(string $root)
    {
        $this->root = $root;
        $this->path = '';
        $this->prefix = [];
        $this->suffix = [];
    }

    public static function create(string $root): PathBuilder
    {
        return new static($root);
    }

    public function getPath(): string
    {
        if (!$this->isPath()) {
            $this->build();
        }
        return $this->path;
    }

    public function getPathAbs(): string
    {
        if (!$this->isPath()) {
            $this->build();
        }
        return $this->root . $this->path;
    }

    public function exists(): PathBuilder
    {
        if (!$this->isPath()) {
            $this->build();
        }
        $path = realpath($this->root . $this->path);
        if (!$path) {
            throw new InvalidArgumentException(sprintf('Path is not exists "%s"', $path));
        }
        return $this;
    }

    public function addPrefix(string $prefix): PathBuilder
    {
        $this->prefix[] = $prefix;
        return $this;
    }

    public function addSuffix(string $suffix): PathBuilder
    {
        $this->suffix[] = $suffix;
        return $this;
    }

    private function isPath(): bool
    {
        return $this->path !== '';
    }

    private function build(): void
    {
        // build prefix
        foreach ($this->prefix as $prefix) {
            $this->path = $prefix . $this->path;
        }
        // build suffix
        foreach ($this->suffix as $suffix) {
            $this->path .= $suffix;
        }
    }

}