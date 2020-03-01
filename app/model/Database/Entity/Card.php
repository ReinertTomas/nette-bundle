<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\IFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\CardRepository")
 */
class Card extends AbstractEntity implements IFile
{

    const NAMESPACE = 'card';

    /**
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(nullable=FALSE)
     */
    protected File $image;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $hidden;

    public function __construct(File $image, string $title, string $description)
    {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->hidden = true;
    }

    public function getImage(): File
    {
        return $this->image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function hide(): void
    {
        $this->hidden = true;
    }

    public function show(): void
    {
        $this->hidden = false;
    }

    public function getNamespace(): string
    {
        return sprintf('/%s/%d', self::NAMESPACE, $this->id);
    }

}