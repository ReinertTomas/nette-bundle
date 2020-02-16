<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Utils\FileSystem;
use Nette\Http\Request;

final class DirectoryManager
{

    private string $basePath;

    private string $baseUrl;

    private string $app;

    private string $temp;

    private string $www;

    private string $upload;

    private string $files;

    public function __construct(string $app, string $upload, string $files, Request $request)
    {
        $this->basePath = $request->getUrl()->getBasePath();
        $this->baseUrl = $request->getUrl()->getBaseUrl();

        $this->app = $this->exists($app);
        $this->temp = $this->exists($app . '/../temp');
        $this->www = $this->exists($app . '/../www');
        $this->upload = $upload;
        $this->files = $files;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getApp(): string
    {
        return $this->app;
    }

    public function getTemp(): string
    {
        return $this->temp;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function getAbsUpload(): string
    {
        return $this->www . $this->upload;
    }

    public function getAbsFiles(): string
    {
        return $this->www . $this->files;
    }

    public function getUrlFiles(): string
    {
        $path = $this->files;
        if ($this->basePath !== '/') {
            $path = $this->basePath . $this->files;
        }
        return $path;
    }

    public function getNamespace(string $namespace): string
    {
        return $this->getAbsFiles() . '/' . $namespace;
    }

    public function findInUpload(string $filename): string
    {
        $path = $this->exists($this->getAbsUpload() . '/' . $filename);
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File not exist in path "%s"', $path));
        }
        return $path;
    }

    public function createInUpload(string $filename): string
    {
        return $this->getAbsFiles() . '/' . $filename;
    }

    public function findInNamespace(string $namespace, string $filename): string
    {
        $path = $this->exists($this->getNamespace($namespace) . '/' . $filename);
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File not exist in path "%s"', $path));
        }
        return $path;
    }

    public function createInNamespace(string $namespace, string $filename): string
    {
        return $this->getNamespace($namespace) . '/' . $filename;
    }

    public function checkNamespace(string $namespace): void
    {
        $newPath = $this->getNamespace($namespace);
        if (!file_exists($newPath)) {
            $this->createNamespace($namespace);
        }
    }

    public function createNamespace(string $namespace): void
    {
        $path = $this->getAbsFiles();
        $list = explode('/', $namespace);
        foreach ($list as $part) {
            $path = $path . '/' . $part;
            FileSystem::createDir($path);
        }
    }

    public function exists(string $path): string
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('Require directory not exists "%s"', $path));
        }
        return $path;
    }

}