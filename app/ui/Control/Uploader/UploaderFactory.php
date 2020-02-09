<?php
declare(strict_types=1);

namespace App\UI\Control\Uploader;

use Nette\Forms\Controls\BaseControl;

final class UploaderFactory
{

    private string $dirUpload;

    public function __construct(string $dirUpload)
    {
        $this->dirUpload = $dirUpload;
    }

    public function create(): UploaderControl
    {
        return new UploaderControl($this->dirUpload);
    }

}