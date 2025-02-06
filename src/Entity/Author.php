<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Birth = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Death = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'Author')]
    private Collection $books;

    /**
     * @var Collection<int, Citation>
     */
    #[ORM\OneToMany(targetEntity: Citation::class, mappedBy: 'Author')]
    private Collection $citations;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->citations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->Birth;
    }

    public function setBirth(?\DateTimeInterface $Birth): static
    {
        $this->Birth = $Birth;

        return $this;
    }

    public function getDeath(): ?\DateTimeInterface
    {
        return $this->Death;
    }

    public function setDeath(?\DateTimeInterface $Death): static
    {
        $this->Death = $Death;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            $book->removeAuthor($this);
        }

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
            $citation->setAuthor($this);
        }

        return $this;
    }

    public function removeCitation(Citation $citation): static
    {
        if ($this->citations->removeElement($citation)) {
            // set the owning side to null (unless already changed)
            if ($citation->getAuthor() === $this) {
                $citation->setAuthor(null);
            }
        }

        return $this;
    }
}
