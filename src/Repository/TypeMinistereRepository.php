<?php

namespace App\Repository;

use App\Entity\TypeMinistere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeMinistere>
 *
 * @method TypeMinistere|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeMinistere|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeMinistere[]    findAll()
 * @method TypeMinistere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeMinistereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeMinistere::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TypeMinistere $entity, bool $flush = true): void
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
    public function remove(TypeMinistere $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TypeMinistere[] Returns an array of TypeMinistere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeMinistere
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
