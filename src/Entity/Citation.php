<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitationRepository::class)]
#[ApiResource]
class Citation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 511)]
    private ?string $Text = null;

    #[ORM\ManyToOne(inversedBy: 'citations')]
    private ?Author $Author = null;

    #[ORM\ManyToOne(inversedBy: 'citations')]
    private ?Book $Book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): static
    {
        $this->Text = $Text;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->Author;
    }

    public function setAuthor(?Author $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->Book;
    }

    public function setBook(?Book $Book): static
    {
        $this->Book = $Book;

        return $this;
    }
}
