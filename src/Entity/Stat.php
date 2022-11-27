<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: StatRepository::class)]
#[UniqueEntity(['apiTimestamp'])]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $newConfirmed = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $totalConfirmed = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $newDeaths = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $totalDeaths = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $newRecovered = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $totalRecovered = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $apiTimestamp = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'stat', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNewConfirmed(): ?int
    {
        return $this->newConfirmed;
    }

    public function setNewConfirmed(int $newConfirmed): self
    {
        $this->newConfirmed = $newConfirmed;

        return $this;
    }

    public function getTotalConfirmed(): ?int
    {
        return $this->totalConfirmed;
    }

    public function setTotalConfirmed(int $totalConfirmed): self
    {
        $this->totalConfirmed = $totalConfirmed;

        return $this;
    }

    public function getNewDeaths(): ?int
    {
        return $this->newDeaths;
    }

    public function setNewDeaths(int $newDeaths): self
    {
        $this->newDeaths = $newDeaths;

        return $this;
    }

    public function getTotalDeaths(): ?int
    {
        return $this->totalDeaths;
    }

    public function setTotalDeaths(int $totalDeaths): self
    {
        $this->totalDeaths = $totalDeaths;

        return $this;
    }

    public function getNewRecovered(): ?int
    {
        return $this->newRecovered;
    }

    public function setNewRecovered(int $newRecovered): self
    {
        $this->newRecovered = $newRecovered;

        return $this;
    }

    public function getTotalRecovered(): ?int
    {
        return $this->totalRecovered;
    }

    public function setTotalRecovered(int $totalRecovered): self
    {
        $this->totalRecovered = $totalRecovered;

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

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
