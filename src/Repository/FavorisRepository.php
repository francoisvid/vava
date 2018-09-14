<?php

namespace App\Repository;

use App\Entity\Favoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Favoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoris[]    findAll()
 * @method Favoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

//    /**
//     * @return Favoris[] Returns an array of Favoris objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findByUserAndEntreprise($utilisateur, $entreprise): ?Favoris{
//        return $this->createQueryBuilder('f')
//            ->Where('f.utilisateur = :user')
//            ->andWhere('f.entreprise = :entreprise')
//            ->setParameter('user', $utilisateur)
//            ->setParameter('entreprise', $entreprise)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
        return $this->findBy(array("utilisateur" => $utilisateur));
    }

}
