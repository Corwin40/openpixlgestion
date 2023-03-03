<?php

namespace App\Repository\Admin;

use App\Entity\Admin\TypeClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Util\Type;

/**
 * @extends ServiceEntityRepository<TypeClient>
 *
 * @method TypeClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeClient[]    findAll()
 * @method TypeClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeClient::class);
    }

    public function save(TypeClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function typeEntreprise($typeEntreprise): bool
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.client', 'c')
            ->addSelect('
                t.isFormCompleted as isFormCompleted,
            ')
            ->andWhere('t.isFormCompleted = :typeEntreprise')
            ->setParameter('typeEntreprise', $typeEntreprise)
            ->getQuery()
            ->getResult()
            ;
    }




//    /**
//     * @return TypeClient[] Returns an array of TypeClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeClient
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
