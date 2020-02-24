<?php
declare(strict_types=1);

namespace App\Model\File;

use App\Model\Database\Entity\File;
use Nette\Http\Request;

final class DirectoryManager
{

    private string $basePath;

    private string $baseUrl;

    private string $app;

    private string $temp;

    private string $resources;

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
        $this->resources = PathBuilder::create($app)
            ->addSuffix('/resources')
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
            ->addSuffix($files)
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

    public function getResources(): string
    {
        return $this->resources;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function getUpload(): PathBuilderInterface
    {
        return $this->upload;
    }

    public function getFiles(): PathBuilderInterface
    {
        return $this->files;
    }

    public function findInUpload(string $filename): PathBuilderInterface
    {
        return PathBuilder::create($this->upload->getPathAbs())
            ->addSuffix('/')
            ->addSuffix($filename)
            ->exists();
    }

    public function findInFiles(File $file): PathBuilderInterface
    {
        return PathBuilder::create($this->files->getPathAbs())
            ->addSuffix($file->getPath())
            ->exists();
    }

    public function createInFiles(string $filename, string $namespace = null): PathBuilderInterface
    {
        $pb = PathBuilder::create($this->files->getPathAbs());

        if ($namespace) {
            $pb->addSuffix($namespace)
                ->generatePath()
                ->exists();
        }

        return $pb->addSuffix('/')
            ->addSuffix($filename);
    }

    public function move(PathBuilderInterface $old, PathBuilderInterface $new): void
    {
        rename($old->getPathAbs(), $new->getPathAbs());
    }

}