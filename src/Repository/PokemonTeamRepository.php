<?php

namespace App\Repository;

use App\Entity\PokemonTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonTeam[]    findAll()
 * @method PokemonTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonTeam::class);
    }

    // /**
    //  * @return PokemonTeam[] Returns an array of PokemonTeam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PokemonTeam
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
