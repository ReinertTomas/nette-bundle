<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\FileRepository")
 * @ORM\Table(name="File")
 * @ORM\HasLifecycleCallbacks
 */
class File extends AbstractEntity
{

    public const THUMB = 'thumb_';

    public const TYPE_IMAGE = 1;
    public const TYPE_FILE = 2;

    public const TYPES = [
        self::TYPE_IMAGE,
        self::TYPE_FILE
    ];

    use TCreatedAt;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected string $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $path;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected string $mime;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $type;

    public function __construct(string $name, string $filename, string $path, string $mime)
    {
        $this->name = $name;
        $this->filename = $filename;
        $this->path = $path;
        $this->mime = $mime;

        $this->type = self::TYPE_FILE;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMime(): string
    {
        return $this->mime;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function isImage(): bool
    {
        return $this->type === self::TYPE_IMAGE;
    }

    public function image(): void
    {
        $this->type = self::TYPE_IMAGE;
    }

}