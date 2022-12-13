<?php

namespace App\Repository;

use App\Entity\Stat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stat>
 *
 * @method Stat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stat[]    findAll()
 * @method Stat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stat::class);
    }

    public function save(Stat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Stat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    public function findOneBySomeField($value): ?Stat
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getSumDataGroupByMonth()
    {
        $em = $this->getEntityManager();
       /* $query = $em->createQuery(
            'SELECT s.apiTimestamp AS date, SUM(s.confirmed) AS confirmed, SUM(s.deaths) AS deaths
            FROM App\Entity\Stat s
            GROUP BY date'
        );*/
        $query = $this->createQueryBuilder('s')
            ->select('s.apiTimestamp')
            ->addSelect('SUM(s.confirmed) as confirmed')
            ->addSelect('SUM(s.deaths) as deaths')
            ->groupBy('s.apiTimestamp');

        return $query->getQuery()->getResult();
    }
}
