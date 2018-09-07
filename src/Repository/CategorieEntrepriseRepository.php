<?php

namespace App\Repository;

use App\Entity\CategorieEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategorieEntreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieEntreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieEntreprise[]    findAll()
 * @method CategorieEntreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategorieEntreprise::class);
    }

//    /**
//     * @return CategorieEntreprise[] Returns an array of CategorieEntreprise objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieEntreprise
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
