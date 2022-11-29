<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[UniqueEntity(fields: ['name'])]
#[UniqueEntity(fields: ['code'])]
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

    #[ORM\Column(length: 255)]
    private ?string $continent = null;

    #[ORM\Column]
    private ?int $population = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $populationDensity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $medianAge = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 3)]
    private ?string $aged65Older = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 3)]
    private ?string $aged70Older = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $gdpPerCapita = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $diabetesPrevalence = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $handwashingFacilities = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $hospitalBedsPerThousand = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $lifeExpectancy = null;

    #[ORM\Column]
    private ?int $newConfirmed = null;

    #[ORM\Column]
    private ?int $totalConfirmed = null;

    #[ORM\Column]
    private ?int $newDeaths = null;

    #[ORM\Column]
    private ?int $totalDeaths = null;

    #[ORM\Column]
    private ?int $newRecovered = null;

    #[ORM\Column]
    private ?int $totalRecovered = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $apiTimestamp = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Stat::class, fetch: 'LAZY', orphanRemoval: true)]
    #[OrderBy(['apiTimestamp' => 'DESC'])]
    private Collection $stats;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
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

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function setContinent(string $continent): self
    {
        $this->continent = $continent;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getPopulationDensity(): ?string
    {
        return $this->populationDensity;
    }

    public function setPopulationDensity(string $populationDensity): self
    {
        $this->populationDensity = $populationDensity;

        return $this;
    }

    public function getMedianAge(): ?string
    {
        return $this->medianAge;
    }

    public function setMedianAge(string $medianAge): self
    {
        $this->medianAge = $medianAge;

        return $this;
    }

    public function getAged65Older(): ?string
    {
        return $this->aged65Older;
    }

    public function setAged65Older(string $aged65Older): self
    {
        $this->aged65Older = $aged65Older;

        return $this;
    }

    public function getAged70Older(): ?string
    {
        return $this->aged70Older;
    }

    public function setAged70Older(string $aged70Older): self
    {
        $this->aged70Older = $aged70Older;

        return $this;
    }

    public function getGdpPerCapita(): ?string
    {
        return $this->gdpPerCapita;
    }

    public function setGdpPerCapita(string $gdpPerCapita): self
    {
        $this->gdpPerCapita = $gdpPerCapita;

        return $this;
    }

    public function getDiabetesPrevalence(): ?string
    {
        return $this->diabetesPrevalence;
    }

    public function setDiabetesPrevalence(string $diabetesPrevalence): self
    {
        $this->diabetesPrevalence = $diabetesPrevalence;

        return $this;
    }

    public function getHandwashingFacilities(): ?string
    {
        return $this->handwashingFacilities;
    }

    public function setHandwashingFacilities(string $handwashingFacilities): self
    {
        $this->handwashingFacilities = $handwashingFacilities;

        return $this;
    }

    public function getHospitalBedsPerThousand(): ?string
    {
        return $this->hospitalBedsPerThousand;
    }

    public function setHospitalBedsPerThousand(string $hospitalBedsPerThousand): self
    {
        $this->hospitalBedsPerThousand = $hospitalBedsPerThousand;

        return $this;
    }

    public function getLifeExpectancy(): ?string
    {
        return $this->lifeExpectancy;
    }

    public function setLifeExpectancy(string $lifeExpectancy): self
    {
        $this->lifeExpectancy = $lifeExpectancy;

        return $this;
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

    /**
     * @return Collection<int, Stat>
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(Stat $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats->add($stat);
            $stat->setCountry($this);
        }

        return $this;
    }

    public function removeStat(Stat $stat): self
    {
        if ($this->stats->removeElement($stat)) {
            // set the owning side to null (unless already changed)
            if ($stat->getCountry() === $this) {
                $stat->setCountry(null);
            }
        }

        return $this;
    }

    public function getFlag(): string
    {
        return strtolower($this->code) . '.png';
    }
}
