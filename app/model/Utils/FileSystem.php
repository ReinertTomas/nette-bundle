<?php
declare(strict_types=1);

namespace App\Model\Utils;

use App\Model\Exception\Logic\InvalidArgumentException;
use Contributte\Utils\FileSystem as ContributteFileSystem;
use Nette\Utils\Random;

final class FileSystem extends ContributteFileSystem
{

    public const IMAGES = [
        'image/jpeg',
        'image/png',
        'image/gif'
    ];

    public static function generateName(string $extension): string
    {
        $now = new DateTime();
        return $now->getTimestamp() . '_' . Random::generate(6, 'A-Za-z0-9') . $extension;
    }

    public static function isImage(string $path): bool
    {
        if (!realpath($path)) {
            throw new InvalidArgumentException(sprintf('Image not exist in the path "%s:', $path));
        }
        return in_array(Strings::lower(mime_content_type($path)), self::IMAGES);
    }

    public static function mime(string $path): string
    {
        if (!realpath($path)) {
            throw new InvalidArgumentException(sprintf('Image not exist in the path "%s:', $path));
        }
        return mime_content_type($path);
    }

}