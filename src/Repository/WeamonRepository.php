<?php

namespace App\Repository;

use App\Entity\Weamon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Weamon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weamon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weamon[]    findAll()
 * @method Weamon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeamonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weamon::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Weamon $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Weamon $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function paginate($dql, $page = 1, $limit = 3)
    {
        $paginator = new Paginator($dql);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function getAllWeamons($currentPage = 1, $limit = 3)
    {
        // Create our query
        $query = $this->createQueryBuilder('p')
            ->getQuery();


        $paginator = $this->paginate($query, $currentPage, $limit);

        return array('paginator' => $paginator, 'query' => $query);
    }

    // /**
    //  * @return Weamon[] Returns an array of Weamon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Weamon
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
