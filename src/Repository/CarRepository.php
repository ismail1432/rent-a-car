<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Car::class);
    }


    public function searchCar($criteria)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cities', 'city')
            ->where('city.name = :cityName')
            ->setParameter("cityName", $criteria['city']->getName())
            ->andWhere('c.color = :color')
            ->setParameter('color', $criteria['color'])
            ->andWhere('c.carburant = :carburant')
            ->setParameter('carburant', $criteria['carburant'])
            ->andWhere('c.price > :minimumPrice')
            ->setParameter('minimumPrice', $criteria['minimumPrice'])
            ->andWhere('c.price < :maximumPrice')
            ->setParameter('maximumPrice', $criteria['maximumPrice'])
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Car
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
