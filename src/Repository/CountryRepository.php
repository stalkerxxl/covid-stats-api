<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function save(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //FIXME вынести в CriteriaFactory + CriteriaTypeEnum
    public static function newConfirmedCriteria(?int $max): Criteria
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt('newConfirmed', 0))
            ->orderBy(['newConfirmed' => Criteria::DESC]);
        if ($max)
            $criteria->setMaxResults($max);
        return $criteria;
    }

    public static function newDeathsCriteria(?int $max): Criteria
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt('newDeaths', 0))
            ->orderBy(['newDeaths' => Criteria::DESC]);
        if ($max)
            $criteria->setMaxResults($max);
        return $criteria;
    }

    public static function newRecoveredCriteria(?int $max): Criteria
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt('newRecovered', 0))
            ->orderBy(['newRecovered' => Criteria::DESC]);
        if ($max)
            $criteria->setMaxResults($max);
        return $criteria;
    }

    public function findAllWithSearchPager(?string $search, int $page, int $limit, ?string $continent,
                                           ?string $sortBy, ?string $direction): Pagerfanta
    {
        $qb = $this->createQueryBuilder('c');

        if ($continent) {
            $qb->andWhere('c.continent = :continent')
                ->setParameter('continent', $continent);
        }
        if ($sortBy && $direction) {
            $qb->orderBy('c.' . $sortBy, $direction);
        } else
            $qb->orderBy('c.name', Criteria::ASC);

        if ($search)
            $qb->andWhere('c.name LIKE :search')
                ->setParameter('search', '%' . $search . '%', Types::STRING);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(new QueryAdapter($qb), $page, $limit);
    }

    public function getAllContinentList(): array|float|int|string
    {
        return $this->createQueryBuilder('c')
            ->select('c.continent')
            ->distinct()
            ->getQuery()
            ->getSingleColumnResult();
    }
}
