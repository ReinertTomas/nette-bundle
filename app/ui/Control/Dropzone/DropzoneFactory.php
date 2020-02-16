<?php
declare(strict_types=1);

namespace App\UI\Control\Dropzone;

use App\Model\Service\DirectoryManager;

final class DropzoneFactory
{

    private DirectoryManager $dm;

    public function __construct(DirectoryManager $dm)
    {
        $this->dm = $dm;
    }

    public function create(): DropzoneControl
    {
        return new DropzoneControl($this->dm->getAbsUpload());
    }

}