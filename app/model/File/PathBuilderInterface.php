<?php
declare(strict_types=1);

namespace App\Model\File;

interface PathBuilderInterface
{

    public function getPath(): string;

    public function getPathAbs(): string;
    
}