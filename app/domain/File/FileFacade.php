<?php
declare(strict_types=1);

namespace App\Domain\File;

use App\Model\Database\Entity\File;
use App\Model\Database\EntityManager;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\File\DirectoryManager;
use App\Model\Utils\FileSystem;
use App\Model\Utils\Image;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class FileFacade
{

    private EntityManager $em;

    private DirectoryManager $dm;

    /** @var array<string, int> */
    private array $thumb;

    public function __construct(EntityManager $em, DirectoryManager $dm)
    {
        $this->em = $em;
        $this->dm = $dm;
        $this->thumb = ['width' => 100, 'height' => 100];
    }

    /**
     * @param array<string, int> $thumb
     */
    public function setThumb(array $thumb): void
    {
        if (!isset($thumb['width']) OR !isset($thumb['height'])) {
            throw new InvalidArgumentException('Unsupported image thumb size');
        }
        $this->thumb = $thumb;
    }

    /**
     * @param string $json
     * @param string|null $dirNamespace
     * @return File[]
     */
    public function createFromJson(string $json, string $dirNamespace = null): array
    {
        try {
            $items = Json::decode($json, Json::FORCE_ARRAY);
        } catch (JsonException $e) {
            throw new InvalidArgumentException(sprintf('Unsupported format. Json decode error "%s".', $json));
        }

        $files = [];

        foreach ($items as $item) {
            $name = $item['name'];
            $oldFilename = $item['filename'];

            // Get file from upload dir
            $oldPathBuilder = $this->dm->findInUpload($oldFilename);
            $mime = FileSystem::mime($oldPathBuilder->getPathAbs());
            $extension = FileSystem::extension($name);
            $newFilename = FileSystem::generateName($extension);
            $newPathBuilder = $this->dm->createInFiles($newFilename, $dirNamespace);

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

            $files[] = $file;
            $this->em->persist($file);
        }

        $this->em->flush();

        return $files;
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