<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\Newsfeeds;
use PHPUnit\Framework\TestCase;

class NewsfeedsTest extends TestCase
{
    /**
     * @param string      $exceptionType
     * @param string|null $externalId
     * @param string      $title
     * @param string      $content
     * @param string      $summary
     * @param string      $image
     *
     * @dataProvider negativeCases
     */
    public function testNegativeCases(
        string $exceptionType,
        ?string $externalId,
        ?string $title,
        ?string $content,
        ?string $summary,
        ?string $image
    ): void {
        $this->expectException($exceptionType);

        Newsfeeds::create(
            $externalId,
            $title,
            $content,
            $summary,
            $image
        );
    }

    public function testPositiveCases(): void
    {
        $newsfeeds = Newsfeeds::create(
            '12345',
            'Title',
            'Content',
            null,
            null
        );

        self::assertSame($newsfeeds->getExternalId(), '12345');
        self::assertSame($newsfeeds->getTitle(), 'Title');
        self::assertSame($newsfeeds->getContent(), 'Content');

        self::assertNull($newsfeeds->getImage());
        self::assertNull($newsfeeds->getSummary());
    }

    /**
     * @return array
     */
    public function negativeCases(): array
    {
        return [
            [\InvalidArgumentException::class, '', 'Title', 'Content', null, null],
            [\TypeError::class, null, 'Title', 'Content', null, null],
            [\InvalidArgumentException::class, '12345', '', 'Content', null, null],
            [\TypeError::class, '12345', null, 'Content', null, null],
            [\InvalidArgumentException::class, '12345', 'Title', '', null, null],
            [\TypeError::class, '12345', 'Title', null, null, null],
        ];
    }
}
