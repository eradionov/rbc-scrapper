<?php

declare(strict_types=1);

namespace App\Handler\Interfaces;

use App\DTO\Newsfeeds;

interface ParserHandleInterface
{
    /**
     * @return array<string, Newsfeeds>
     */
    public function doParse(): array;
}
