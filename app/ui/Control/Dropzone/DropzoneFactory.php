<?php
declare(strict_types=1);

namespace App\UI\Control\Dropzone;

use App\Model\File\DirectoryManager;

final class DropzoneFactory
{

    private DirectoryManager $dm;

    public function __construct(DirectoryManager $dm)
    {
        $this->dm = $dm;
    }

    public function create(bool $onlyImages = false): DropzoneControl
    {
        return new DropzoneControl(
            $this->dm->getUpload()
                ->getPathAbs()
        );
    }

}