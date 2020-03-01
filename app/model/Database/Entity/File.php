<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class File extends AbstractEntity
{

    public const TYPE_DOCUMENT = 1;
    public const TYPE_IMAGE = 2;

    public const TYPES = [
        self::TYPE_DOCUMENT,
        self::TYPE_IMAGE
    ];

    use TCreatedAt;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected string $path;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected string $mime;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    protected int $type;

    public function __construct(string $name, string $path, string $mime)
    {
        $this->name = $name;
        $this->path = $path;
        $this->mime = $mime;
        $this->type = self::TYPE_DOCUMENT;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilename(): string
    {
        $position = strrpos($this->path, '/');
        if ($position === false) {
            throw new InvalidArgumentException(sprintf('Invalid path format "%s"', $this->path));
        }
        return substr($this->path, $position + 1);
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

    public function getThumb(): string
    {
        $position = strrpos($this->path, '.');
        if ($position === false) {
            throw new InvalidArgumentException(sprintf('Invalid path format "%s"', $this->path));
        }

        $filepath = substr($this->path, 0, $position);
        $extension = substr($this->path, $position);

        return $filepath . '_thumb' . $extension;
    }

    public function isDocument(): bool
    {
        return $this->type === self::TYPE_DOCUMENT;
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