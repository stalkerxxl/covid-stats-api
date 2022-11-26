<?php

namespace App\Entity;

use App\Repository\CovidRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CovidRepository::class)]
class Covid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $confirmed = null;

    #[ORM\Column]
    private ?int $deaths = null;

    #[ORM\Column]
    private ?int $recovered = null;

    #[ORM\Column]
    private ?int $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $apiTimestamp = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'covids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfirmed(): ?int
    {
        return $this->confirmed;
    }

    public function setConfirmed(int $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getDeaths(): ?int
    {
        return $this->deaths;
    }

    public function setDeaths(int $deaths): self
    {
        $this->deaths = $deaths;

        return $this;
    }

    public function getRecovered(): ?int
    {
        return $this->recovered;
    }

    public function setRecovered(int $recovered): self
    {
        $this->recovered = $recovered;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getApiTimestamp(): ?\DateTimeImmutable
    {
        return $this->apiTimestamp;
    }

    public function setApiTimestamp(\DateTimeImmutable $apiTimestamp): self
    {
        $this->apiTimestamp = $apiTimestamp;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
