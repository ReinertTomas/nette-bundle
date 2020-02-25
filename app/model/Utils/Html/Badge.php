<?php
declare(strict_types=1);

namespace App\Model\Utils\Html;

use App\Model\Utils\Html;

final class Badge
{

    private Html $element;

    public function __construct()
    {
        $this->element = Html::el('span');
        $this->element->class = 'badge badge-default';
    }

    public static function create(): Badge
    {
        return new static();
    }

    public function setText(string $text): Badge
    {
        $this->element->setText($text);
        return $this;
    }

    public function setBackground(string $bg): Badge
    {
        $this->element->class = "badge badge-{$bg}";
        return $this;
    }

    public function toString(): string
    {
        return $this->element->toHtml();
    }

}