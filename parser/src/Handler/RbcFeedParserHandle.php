<?php

declare(strict_types=1);

namespace App\Handler;

use App\DTO\Newsfeeds;
use App\Exception\ParserException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Parser for parsing rbc.ru.
 */
class RbcFeedParserHandle extends AbstractParserHandle
{
    private const PARSER_LINK_FEEDS = 'https://www.rbc.ru/v10/ajax/get-news-feed/project/rbcnews/lastDate/%d/limit/%d?_=%d';

    private const FEED_LINK_REGEXP = '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]+id=([\'"])id_newsfeed_(?<external_id>.+?)\1[^>]*>/i';

    /**
     * {@inheritdoc}
     */
    public function doParse(): array
    {
        $feeds = [];

        foreach ($this->requestFeedLinks() as $link) {
            $crawler = $this->parserClient->request('GET', $link['link']);

            try {
                $article = Newsfeeds::create(
                    $link['external_id'],
                    $this->parseTitle($crawler),
                    $this->parseContent($crawler),
                    $this->parserSummary($crawler),
                    $this->parseImage($crawler)
                );

                $errors = $this->validator->validate($article);

                if (count($errors) > 0) {
                    throw new ParserException(implode(PHP_EOL, $errors));
                }

                $feeds[$link['external_id']] = $article;
            } catch (\Throwable $exception) {
                $this->logger->error(
                    sprintf('Failes to parse: %s. Error: %s',
                        $link['link'],
                        $exception->getMessage())
                );
            }
        }

        return $feeds;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandleType(): string
    {
        return  'rbc_handler';
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     */
    private function parseTitle(Crawler $crawler): string
    {
        return strip_tags(
            $crawler->filter('.article__header__title')
                ->children()
                ->text()
        );
    }

    /**
     * @param Crawler $crawler
     *
     * @return string|null
     */
    private function parserSummary(Crawler $crawler): ?string
    {
        if ($crawler->filter('.article__header__anons,.article__text__overview')->getNode(0) !== null) {
            return strip_tags($crawler->filter('.article__header__anons,.article__text__overview')->text());
        }

        return null;
    }

    /**
     * @param Crawler $crawler
     *
     * @return string|null
     */
    private function parseImage(Crawler $crawler): ?string
    {
        if (
            null !== $crawler->filter('.article__main-image,.article__picture')->getNode(0)
            && null !== $crawler->filter('.article__main-image,.article__picture')
                ->children('div > img,img')
                ->getNode(0)
        ) {
            $imageSource = $crawler->filter('.article__main-image,.article__picture')
                ->children('div > img,img')
                ->attr('src');

            file_put_contents(
                $this->imageUploadFolder.basename($imageSource),
                file_get_contents($imageSource)
            );

            return basename($imageSource);
        }

        return null;
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     */
    private function parseContent(Crawler $crawler): string
    {
        $content = '';

        if (null === $crawler->filter('.article__text,.l-base__col__main,.article__text__pro')->getNode(0)) {
            throw new \LogicException('Content can\'t be empty. Probably landing page is parsing');
        }

        $crawler->filter('.article__text,.l-base__col__main,.article__text__pro')->each(function (Crawler $node) use (&$content) {
            $node->children(implode(',', self::ALLOWED_TAGS))->each(function ($node) use (&$content) {
                $content .= strip_tags($node->outerHtml(), self::ALLOWED_TAGS);
            });
        });

        return trim($content);
    }

    /**
     * Parses feed news json array for news links.
     *
     * @return array
     */
    private function requestFeedLinks(): array
    {
        $currentTime = time();
        $links = [];

        $this->parserClient->request(
            'GET',
            sprintf(self::PARSER_LINK_FEEDS, $currentTime, self::FEED_LIMIT, $currentTime)
        );

        if (!empty($this->parserClient->getResponse()->getContent())) {
            $response = json_decode($this->parserClient->getResponse()->getContent(), true);

            foreach ($response['items'] as $item) {
                if (!isset($item['html'])) {
                    continue;
                }

                $match = preg_match(self::FEED_LINK_REGEXP, $item['html'], $result);

                if (!$match || empty($result['href']) || empty($result['external_id'])) {
                    continue;
                }

                $links[] = [
                    'link' => $result['href'],
                    'external_id' => $result['external_id'],
                ];
            }
        }

        return $links;
    }
}
