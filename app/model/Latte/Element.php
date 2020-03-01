<?php
declare(strict_types=1);

namespace App\Model\Latte;

final class Element
{

    protected string $text;

    protected ?string $bg;

    protected ?string $icon;

    public function __construct(string $text)
    {
        $this->text = $text;
        $this->bg = null;
        $this->icon = null;
    }

    public static function create(string $text): Element
    {
        return new static($text);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getBg(): ?string
    {
        return $this->bg;
    }

    public function setBg(string $bg): Element
    {
        $this->bg = $bg;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): Element
    {
        $this->icon = $icon;
        return $this;
    }

    public function hasIcon(): bool
    {
        return (bool) $this->icon;
    }

}