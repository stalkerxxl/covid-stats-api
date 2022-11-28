<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[UniqueEntity(fields: ['name', 'slug', 'code'])]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Covid::class, orphanRemoval: true)]
    private Collection $covids;

    #[ORM\OneToOne(mappedBy: 'country', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private ?Stat $stat = null;

    public function __construct()
    {
        $this->covids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    /**
     * @return Collection<int, Covid>
     */
    public function getCovids(): Collection
    {
        return $this->covids;
    }

    public function addCovid(Covid $covid): self
    {
        if (!$this->covids->contains($covid)) {
            $this->covids->add($covid);
            $covid->setCountry($this);
        }

        return $this;
    }

    public function removeCovid(Covid $covid): self
    {
        if ($this->covids->removeElement($covid)) {
            // set the owning side to null (unless already changed)
            if ($covid->getCountry() === $this) {
                $covid->setCountry(null);
            }
        }

        return $this;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(Stat $stat): self
    {
        // set the owning side of the relation if necessary
        if ($stat->getCountry() !== $this) {
            $stat->setCountry($this);
        }

        $this->stat = $stat;

        return $this;
    }
}
