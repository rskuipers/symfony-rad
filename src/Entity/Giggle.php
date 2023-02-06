<?php

namespace App\Entity;

use App\Repository\GiggleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GiggleRepository::class)]
class Giggle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chuckle $chuckle = null;

    #[ORM\ManyToOne(inversedBy: 'giggles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $giggler = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChuckle(): ?Chuckle
    {
        return $this->chuckle;
    }

    public function setChuckle(?Chuckle $chuckle): self
    {
        $this->chuckle = $chuckle;

        return $this;
    }

    public function getGiggler(): ?User
    {
        return $this->giggler;
    }

    public function setGiggler(?User $giggler): self
    {
        $this->giggler = $giggler;

        return $this;
    }
}
