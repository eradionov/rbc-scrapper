<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\News;
use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    /**
     * @dataProvider negativeCases
     */
    public function testNegativeCases(
        ?string $externalId,
        ?string $title,
        ?string $content,
        ?string $summary,
        ?string $image,
        string $exceptionType
    ): void {
        if (!empty($exceptionType)) {
            $this->expectException($exceptionType);
        }

        $news = News::create(
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

    public function negativeCases(): array
    {
        return [
            ['', 'Title', 'Content', null, null],
            [null, 'Title', 'Content', null, null, \TypeError::class],
            ['12345', '', 'Content', null, null],
            ['12345', null, 'Content', null, null, \TypeError::class],
            ['12345', 'Title', '', null, null],
            ['12345', 'Title', null, null, null, \TypeError::class],
        ];
    }
}
