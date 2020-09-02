<?php

declare(strict_types=1);

namespace App\Factory\Interfaces;

use App\Handler\AbstractParserHandle;

/**
 * Parser handle getter interface.
 */
interface FactoryParserHandleGetterInterface
{
    /**
     * @param string $name Defines parser handle type.
     *
     * @return AbstractParserHandle|null Returns instance of parser handle class.
     */
    public function getParserHandle(string $name): ?AbstractParserHandle;

    /**
     * Returns all available parser handles.
     *
     * @return array
     */
    public function getAll(): array;
}
