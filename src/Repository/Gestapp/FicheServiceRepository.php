<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\FicheService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheService>
 *
 * @method FicheService|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheService|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheService[]    findAll()
 * @method FicheService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheService::class);
    }

    public function save(FicheService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FicheService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listByClient($idclient): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.Client', 'c')
            ->leftJoin('f.service', 's')
            ->addSelect('
                f.priceHour as priceHour,
                f.priceBundle as priceBundle,
                f.name as name,
                f.echeance as echeance,
                f.updatedAt,
                f.statut,
                f.createdAt,
                f.engagement,
                f.package,
                f.id AS id,
                s.id AS idService,
                s.name AS nameService
            ')
            ->andWhere('c.id = :idclient')
            ->setParameter('idclient', $idclient)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function listByServ($idserv): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.Client', 'c')
            ->leftJoin('f.service', 's')
            ->addSelect('
                c.nameStructure as nameStructure,
                c.firstName as firstName,
                c.lastName as lastName,
                f.echeance as echeance,
                f.updatedAt,
                f.statut,
                f.createdAt,
                f.engagement,
                f.package,
                f.id AS id,
                s.id AS idService,
                s.name AS nameService
            ')
            ->andWhere('s.id = :idserv')
            ->setParameter('idserv', $idserv)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return FicheService[] Returns an array of FicheService objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FicheService
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
