<?php

declare(strict_types=1);

namespace App\Factory\Interfaces;

use App\Handler\AbstractParserHandle;

/**
 * Parser handle setter interface.
 */
interface FactoryParserHandleSetterInterface
{
    /**
     * @param AbstractParserHandle $parserHandler Adds parser handle to be invoked later.
     */
    public function addParserHandle(AbstractParserHandle $parserHandler): void;
}
