<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $Author;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReleaseDate = null;

    /**
     * @var Collection<int, Citation>
     */
    #[ORM\OneToMany(targetEntity: Citation::class, mappedBy: 'Book')]
    private Collection $citations;

    public function __construct()
    {
        $this->Author = new ArrayCollection();
        $this->citations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthor(): Collection
    {
        return $this->Author;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->Author->contains($author)) {
            $this->Author->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->Author->removeElement($author);

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->ReleaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $ReleaseDate): static
    {
        $this->ReleaseDate = $ReleaseDate;

        return $this;
    }

    /**
     * @return Collection<int, Citation>
     */
    public function getCitations(): Collection
    {
        return $this->citations;
    }

    public function addCitation(Citation $citation): static
    {
        if (!$this->citations->contains($citation)) {
            $this->citations->add($citation);
            $citation->setBook($this);
        }

        return $this;
    }

    public function removeCitation(Citation $citation): static
    {
        if ($this->citations->removeElement($citation)) {
            // set the owning side to null (unless already changed)
            if ($citation->getBook() === $this) {
                $citation->setBook(null);
            }
        }

        return $this;
    }
}
