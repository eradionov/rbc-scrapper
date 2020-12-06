<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @ORM\Table(
 *     name="news",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="UK_external_id", columns={"external_id"})},
 *     indexes={@ORM\Index(name="external_idx", columns={"external_id"})}
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     */
    private string $externalId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private string $content;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTimeInterface $dateCreated;

    /**
     * @param string      $externalId Article external id.
     * @param string      $title      Article title.
     * @param string      $content    Article content.
     * @param string|null $summary    Article summary
     * @param string|null $image      Article main image
     */
    public function __construct(
        string $externalId,
        string $title,
        string $content,
        ?string $summary,
        ?string $image
    ) {
        $this->externalId = $externalId;
        $this->title = $title;
        $this->content = $content;
        $this->summary = $summary;
        $this->image = $image;
    }

    /**
     * @param string      $externalId Article external id.
     * @param string      $title      Article title.
     * @param string      $content    Article content.
     * @param string|null $summary    Article summary
     * @param string|null $image      Article main image
     *
     * @return News
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    /**
     *  @ORM\PrePersist()
     */
    public function setDateCreated(): void
    {
        $this->dateCreated = new \DateTimeImmutable();
    }
}
