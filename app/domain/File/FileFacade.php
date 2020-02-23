<?php
declare(strict_types=1);

namespace App\Domain\File;

use App\Model\Database\Entity\File;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\File\DirectoryManager;
use App\Model\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class FileFacade
{

    private DirectoryManager $dm;

    public function __construct(DirectoryManager $dm)
    {
        $this->dm = $dm;
    }


    public function createFromJson(string $json)
    {
        try {
            $items = Json::decode($json, Json::FORCE_ARRAY);
        } catch (JsonException $e) {
            throw new InvalidArgumentException(sprintf('Unsupported format. Json decode error "%s".', $json));
        }

        $files = [];
        foreach ($items as $item) {
            $name = $item['name'];
            $filename = $item['filename'];

            // Get file from upload dir
            $oldPath = $this->dm->findInUpload($filename);

            $mime = mime_content_type($oldPath);
            $extension = FileSystem::extension($name);
            $newFilename = FileSystem::generateName($extension);
            $newPath = $this->dm->createInFiles($newFilename);

            dump($oldPath);
            dump($name);
            dump($newPath);
            dump($mime);

//            rename($oldPath, $newPath);

            $file = new File(
                $name,
                $newFilename,
                $this->dm->getFiles() . '/' . $newFilename,
                $mime
            );

            if (FileSystem::isImage($file->getMime())) {
                $file->image();
            }

            $files[] = $file;
        }

        dump($files);
        dump('create');
        die();
    }

    public function createThumb(File $file): void
    {
        if (!$file->isImage()) {
            throw new InvalidArgumentException("Unsupported format, only images");
        }

        $path = $this->dm->createInFiles(File::THUMB . $file->getFilename());

        $image = Image::fromFile($file->getPath());
        $image->resize(100, 100, Image::EXACT);
        $image->sharpen();
        $image->save($path, 80, Image::JPEG);
    }

}