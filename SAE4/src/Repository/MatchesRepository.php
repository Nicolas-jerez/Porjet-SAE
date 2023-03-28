<?php

namespace App\Repository;

use App\Entity\Matches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matches>
 *
 * @method Matches|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matches|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matches[]    findAll()
 * @method Matches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matches::class);
    }

    public function save(Matches $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Matches $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get3LastMatch(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
                SELECT c.equipe_locale, c.equipe_adverse, c.date_heure, c.score FROM App\Entity\Matches c
                where c.date_heure <= :currentdate
                order By c.date_heure DESC')
            ->setParameter('currentdate', new \DateTime('@'.strtotime('now')))
            ->setMaxResults(3);
        return $query->getResult();
    }

    public function get3NextMatch(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
                SELECT c.equipe_locale, c.equipe_adverse, c.date_heure FROM App\Entity\Matches c
                where c.date_heure >= :currentdate
                order By c.date_heure ASC')
            ->setParameter('currentdate', new \DateTime('@'.strtotime('now')))
            ->setMaxResults(3);
        return $query->getResult();
    }

    public function getMatchesFromLibelle(String $libelle): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
                SELECT c FROM App\Entity\Matches c
                where c.equipe_locale = :equipe
                order By c.date_heure ASC')
            ->setParameter('equipe', $libelle)
            ->setMaxResults(15);
        return $query->getResult();
    }
//    /**
//     * @return Matches[] Returns an array of Matches objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matches
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
