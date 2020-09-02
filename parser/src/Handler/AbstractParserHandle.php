<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Log\LoggerInterface;
use App\Handler\Interfaces\ParserHandleInterface;
use App\Parser\Interfaces\ContentParserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractParserHandle implements ParserHandleInterface
{
    protected const FEED_LIMIT = 15;

    protected const ALLOWED_TAGS = ['p', 'a', 'ul', 'li', 'strong', 'b', 'span', 'h1', 'h2', 'h3', 'ol'];

    protected string $imageUploadFolder;

    /**
     * @var ContentParserInterface
     */
    protected ContentParserInterface $parserClient;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @param ContentParserInterface $parserClient
     * @param LoggerInterface        $logger
     * @param ValidatorInterface     $validator
     * @param string                 $imageUploadFolder
     */
    public function __construct(
        ContentParserInterface $parserClient,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        string $imageUploadFolder
    ) {
        $this->imageUploadFolder = $imageUploadFolder;
        $this->parserClient = $parserClient;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    abstract public function getHandleType(): string;
}
