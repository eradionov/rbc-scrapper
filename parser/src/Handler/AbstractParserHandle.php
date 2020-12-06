<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Log\LoggerInterface;
use App\Handler\Interfaces\ParserHandleInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractParserHandle implements ParserHandleInterface
{
    protected const FEED_LIMIT = 15;

    protected const ALLOWED_TAGS = ['p', 'a', 'ul', 'li', 'strong', 'b', 'span', 'h1', 'h2', 'h3', 'ol'];

    /**
     * @param AbstractBrowser $parserClient
     * @param LoggerInterface        $logger
     * @param ValidatorInterface     $validator
     * @param string                 $imageUploadFolder
     */
    public function __construct(
        protected AbstractBrowser $parserClient,
        protected LoggerInterface $logger,
        protected ValidatorInterface $validator,
        protected string $imageUploadFolder
    ) {
    }

    /**
     * @return string
     */
    abstract public function getHandleType(): string;
}
