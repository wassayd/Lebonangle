<?php

namespace App\Entity;

use App\Controller\api\GetPublishedAdverts;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdvertRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"={"controller"=GetPublishedAdverts::class},"post"},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ApiFilter(OrderFilter::class, properties={"publishedAt","price"})
 * @ApiFilter(RangeFilter::class, properties={"price"})
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups("write")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Le titre doit avoir au moins {{ limit }} lettres",
     *      maxMessage = "Le titre ne doit pas dépasser {{ limit }} lettres",
     * )
     */
    private ?string $title;

    /**
     * @Groups("write")
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      max = 1200,
     *      maxMessage = "Le contenu ne doit pas dépasser {{ limit }} lettres",
     * )
     */
    private ?string $content;

    /**
     * @Groups("write")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $author;

    /**
     * @Groups("write")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email;

    /**
     * @Groups("write")
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     * @ApiFilter(SearchFilter::class, properties={"category.id": "iexact"})
     */
    private ?Category $category;

    /**
     * @Groups("write")
     * @ORM\Column(type="float")
     * @Assert\Range(
     *      min = 1,
     *      max = 1000000,
     *      notInRangeMessage = "Le prix doit être compris entre {{ min }} et {{ max }} €",
     * )
     */
    private ?float $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $state;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $publishedAt;

    /**
     * @Groups("write")
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="advert", orphanRemoval=true)
     */
    private ?Collection $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAdvert($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAdvert() === $this) {
                $picture->setAdvert(null);
            }
        }

        return $this;
    }
}
