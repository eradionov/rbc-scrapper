<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\AbstractParserHandle;
use App\Factory\Interfaces\FactoryHandleInterface;

/**
 * Used to aggregate of parser handlers for later invocation.
 */
class ParserHandleFactory implements FactoryHandleInterface
{
    /**
     * @var array<AbstractParserHandle>
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
     * @param AbstractParserHandle $parserHandler
     */
    public function addParserHandle(AbstractParserHandle $parserHandler): void
    {
        $this->parserHandleItems[$parserHandler->getHandleType()] = $parserHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->parserHandleItems;
    }
}
