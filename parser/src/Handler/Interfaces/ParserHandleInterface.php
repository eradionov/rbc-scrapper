<?php

declare(strict_types=1);

namespace App\Handler\Interfaces;

interface ParserHandleInterface
{
    /**
     * @return array
     */
    public function doParse(): array;
}
