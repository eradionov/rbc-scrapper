<?php

declare(strict_types=1);

namespace App\Parser;

use Goutte\Client;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\BrowserKit\CookieJar;
use App\Parser\Interfaces\ContentParserInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Wrapper class for Goutte parser client.
 */
class GoutteParser extends Client implements ContentParserInterface
{
    /**
     * @param HttpClientInterface|null $client
     * @param History|null             $history
     * @param CookieJar|null           $cookieJar
     */
    public function __construct(HttpClientInterface $client = null, History $history = null, CookieJar $cookieJar = null)
    {
        parent::__construct($client, $history, $cookieJar);
    }
}
