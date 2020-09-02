<?php

declare(strict_types=1);

namespace App\DTO;

class Newsfeeds
{
    /**
     * @var string
     */
    private string $externalId;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var string|null
     */
    private ?string $summary;

    /**
     * @var string|null
     */
    private ?string $image;

    /**
     * @param string      $externalId
     * @param string      $title
     * @param string      $content
     * @param string|null $summary
     * @param string|null $image
     */
    public function __construct(
        string $externalId,
        string $title,
        string $content,
        ?string $summary,
        ?string $image
    ) {
        $this->setExternalId($externalId);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setImage($image);
        $this->setSummary($summary);
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $externalId
     *
     * @throws \InvalidArgumentException
     *                                   If value is blank, or null
     */
    private function setExternalId(string $externalId): void
    {
        if (empty($externalId)) {
            throw new \InvalidArgumentException('External id can\'t be empty');
        }

        $this->externalId = $externalId;
    }

    /**
     * @param string $title
     *
     * @throws \InvalidArgumentException
     *                                   If value is blank, or null
     */
    private function setTitle(string $title): void
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Title can\'t be empty');
        }

        $this->title = $title;
    }

    /**
     * @param string $content
     *
     * @throws \InvalidArgumentException
     *                                   If value is blank, or null
     */
    private function setContent(string $content): void
    {
        if (empty($content)) {
            throw new \InvalidArgumentException('Content can\'t be empty');
        }

        $this->content = $content;
    }

    /**
     * @param string|null $summary
     */
    private function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @param string|null $image
     */
    private function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @param string      $externalId
     * @param string      $title
     * @param string      $content
     * @param string|null $summary
     * @param string|null $image
     *
     * @return Newsfeeds
     */
    public static function create(
        string $externalId,
        string $title,
        string $content,
        ?string $summary,
        ?string $image
    ): self {
        return new self(
            $externalId,
            $title,
            $content,
            $summary,
            $image
        );
    }
}
