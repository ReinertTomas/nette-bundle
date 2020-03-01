<?php
declare(strict_types=1);

namespace App\Domain\File;

use App\Model\Database\Entity\File;
use App\Model\Database\EntityManager;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\File\DirectoryManager;
use App\Model\File\FileFactory;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class FileFacade
{

    private FileFactory $factory;

    private EntityManager $em;

    private DirectoryManager $dm;

    public function __construct(FileFactory $factory, EntityManager $em, DirectoryManager $dm)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->dm = $dm;
    }

    /**
     * @param string $json
     * @param string $namespace
     * @return File[]
     */
    public function createFromJson(string $json, string $namespace): array
    {
        try {
            $items = Json::decode($json, Json::FORCE_ARRAY);
        } catch (JsonException $e) {
            throw new InvalidArgumentException(sprintf('Unsupported format. Json decode error "%s".', $json));
        }

        $files = [];
        foreach ($items as $item) {
            $file = $this->factory->createFile($item['name'], $item['filename'], $namespace);
            $files[] = $file;

            $this->em->persist($file);
        }

        $this->em->flush();

        return $files;
    }

}