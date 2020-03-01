<?php
declare(strict_types=1);

namespace App\Model\File;

use App\Model\Database\Entity\File;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Utils\FileSystem;
use App\Model\Utils\Image;

final class FileFactory
{

    private DirectoryManager $dm;

    /** @var array<string, int> */
    private array $thumb;

    public function __construct(DirectoryManager $dm)
    {
        $this->dm = $dm;
        $this->thumb = ['width' => 100, 'height' => 100];
    }

    /**
     * @param array<string, int> $thumb
     */
    public function setThumb(array $thumb): void
    {
        $this->thumb = $thumb;
    }

    public function createFile(string $name, string $filename, string $namespace): File
    {
        $oldPathBuilder = $this->dm->findInUpload($filename);
        $mime = FileSystem::mime($oldPathBuilder->getPathAbs());
        $extension = FileSystem::extension($name);
        $newFilename = FileSystem::generateName($extension);
        $newPathBuilder = $this->dm->createInFiles($newFilename, $namespace);

        // Move file
        $this->dm->move($oldPathBuilder, $newPathBuilder);

        $file = new File(
            $name,
            $newPathBuilder->getPath(),
            $mime
        );

        if (FileSystem::isImage($newPathBuilder->getPathAbs())) {
            $file->image();
            $this->createThumb($file);
        }

        return $file;
    }

    public function createThumb(File $file): void
    {
        if (!$file->isImage()) {
            throw new InvalidArgumentException("Unsupported format, only images");
        }

        $imagePathBuilder = $this->dm->findInFiles($file);
        $thumbPathBuilder = $this->dm->createInFiles($file->getThumb());

        $image = Image::fromFile($imagePathBuilder->getPathAbs());
        $image->resize($this->thumb['width'], $this->thumb['height'], Image::EXACT);
        $image->sharpen();
        $image->save($thumbPathBuilder->getPathAbs(), 80, Image::JPEG);
    }

}