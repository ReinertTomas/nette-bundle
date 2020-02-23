<?php
declare(strict_types=1);

namespace App\Model\File;

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

    private PathBuilder $upload;

    private PathBuilder $files;

    public function __construct(string $app, string $upload, string $files, Request $request)
    {
        $this->basePath = $request->getUrl()->getBasePath();
        $this->baseUrl = $request->getUrl()->getBaseUrl();

        $this->app = $app;
        $this->temp = PathBuilder::create($app)
            ->addSuffix('/../temp')
            ->exists()
            ->getPathAbs();
        $this->www = PathBuilder::create($app)
            ->addSuffix('/../www')
            ->exists()
            ->getPathAbs();
        $this->upload = PathBuilder::create($this->www)
            ->addSuffix($upload)
            ->exists();
        $this->files = PathBuilder::create($this->www)
            ->addPrefix($files)
            ->exists();
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

    public function getUpload(bool $isAbsolute = false): string
    {
        return $isAbsolute ? $this->upload->getPathAbs() : $this->upload->getPath();
    }

    public function getFiles(bool $isAbsolute = false): string
    {
        return $isAbsolute ? $this->files->getPathAbs() : $this->upload->getPath();
    }

    public function findInUpload(string $filename): string
    {
        $path = PathBuilder::create($this->upload->getPathAbs())
            ->addSuffix('/')
            ->addSuffix($filename)
            ->exists()
            ->getPathAbs();
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File not exist in path "%s"', $path));
        }
        return $path;
    }

    public function createInFiles(string $path): string
    {
        return PathBuilder::create($this->files->getPathAbs())
            ->addSuffix('/')
            ->addSuffix($path)
            ->getPathAbs();
    }

}