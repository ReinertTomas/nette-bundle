<?php
declare(strict_types=1);

namespace App\Model\Exception\Logic;

use App\Model\Exception\LogicException;

final class InvalidArgumentException extends LogicException
{

    /**
     * @param string|int $arg
     * @return InvalidArgumentException
     */
    public static function create($arg): InvalidArgumentException
    {
        return new static('Unsupported argument' . $arg);
    }

}