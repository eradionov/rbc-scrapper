<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\AbstractParserHandle;
use App\Factory\Interfaces\FactoryHandleInterface;

/**
 * Chain of responsibility, that is used to aggregate of parser handles
 * and invoke later.
 */
class ParserHandleFactory implements FactoryHandleInterface
{
    /**
     * @var array
     */
    private array $parserHandleItems = [];

    /**
     * @param string $name Parser handle name
     *
     * @return AbstractParserHandle|null
     */
    public function getParserHandle(string $name): ?AbstractParserHandle
    {
        return $this->parserHandleItems[$name] ?? null;
    }

    /**
     * @param AbstractParserHandle $parserHandle
     */
    public function addParserHandle(AbstractParserHandle $parserHandle): void
    {
        $this->parserHandleItems[$parserHandle->getHandleType()] = $parserHandle;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->parserHandleItems;
    }
}
