<?php
declare(strict_types=1);

namespace App\Model\File;

use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Utils\FileSystem;

final class PathBuilder implements PathBuilderInterface
{

    private string $root;

    private bool $realpath;

    /** @var array<int, string> */
    private array $suffix;

    public function __construct(string $root)
    {
        $this->root = $root;
        $this->realpath = false;
        $this->suffix = [];
    }

    public static function create(string $root): PathBuilder
    {
        return new static($root);
    }

    public function getPath(): string
    {
        return $this->buildPath();
    }

    public function getPathAbs(): string
    {
        $path = $this->root . $this->buildPath();
        return $this->realpath ? realpath($path) : $path;
    }

    public function exists(): PathBuilder
    {
        $path = realpath($this->root . $this->buildPath());
        if (!$path) {
            throw new InvalidArgumentException(sprintf('Path is not exists "%s"', $path));
        }
        $this->realpath = true;
        return $this;
    }

    public function generatePath(): PathBuilder
    {
        $dirs = explode('/', $this->buildPath());
        $path = $this->root;
        foreach ($dirs as $dir) {
            if (empty($dir))
            {
                continue;
            }
            $path = $path . '/' . $dir;
            if (!realpath($path)) {
                FileSystem::createDir($path);
            }
        }
        return $this;
    }

    public function addSuffix(string $suffix): PathBuilder
    {
        $this->realpath = false;
        $this->suffix[] = $suffix;
        return $this;
    }

    private function buildPath(): string
    {
        $path = '';
        foreach ($this->suffix as $suffix) {
            $path .= $suffix;
        }
        return $path;
    }

}