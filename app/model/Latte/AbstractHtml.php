<?php
declare(strict_types=1);

namespace App\Model\Latte;

use App\Model\Exception\Logic\InvalidArgumentException;

abstract class AbstractHtml
{

    /**
     * @param string $key
     * @param Element[] $collection
     * @return Element
     */
    protected function findByString(string $key, array $collection): Element
    {
        if (!array_key_exists($key, $collection)) {
            InvalidArgumentException::create($key);
        }
        return $collection[$key];
    }

    /**
     * @param int $key
     * @param Element[] $collection
     * @return Element
     */
    protected function findByInt(int $key, array $collection): Element
    {
        if (!array_key_exists($key, $collection)) {
            InvalidArgumentException::create($key);
        }
        return $collection[$key];
    }

    /**
     * @param Element[] $collection
     * @return array<mixed, string>
     */
    protected function toSelect(array $collection): array
    {
        $array = [];
        foreach ($collection as $key => $element) {
            $array[$key] = $element->getText();
        }
        return $array;
    }

}