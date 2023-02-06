<?php

namespace App\Repository;

use App\Entity\Chuckle;
use App\Entity\Giggle;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Giggle>
 *
 * @method Giggle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Giggle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Giggle[]    findAll()
 * @method Giggle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiggleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Giggle::class);
    }

    public function save(Giggle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Giggle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Giggle[] Returns an array of Giggle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Giggle
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getGigglesFor(Chuckle $chuckle): int
    {
        return (int)$this->createQueryBuilder('g')
            ->andWhere('g.chuckle = :chuckle')
            ->setParameter('chuckle', $chuckle)
            ->select('COUNT(1)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function toggleGiggle(User $giggler, Chuckle $chuckle): void
    {
        if ($this->hasGiggledBefore($giggler, $chuckle)) {
            $this->removeGiggle($giggler, $chuckle);
            return;
        }

        $this->addGiggle($giggler, $chuckle);
    }

    private function addGiggle(User $giggler, Chuckle $chuckle): void
    {
        $giggle = new Giggle();
        $giggle->setGiggler($giggler);
        $giggle->setChuckle($chuckle);

        $this->getEntityManager()->persist($giggle);
        $this->getEntityManager()->flush();
    }

    private function removeGiggle(User $giggler, Chuckle $chuckle): void
    {
        $giggle = $this->findOneBy(['giggler' => $giggler, 'chuckle' => $chuckle]);

        if (!$giggle) {
            return;
        }

        $this->getEntityManager()->remove($giggle);
        $this->getEntityManager()->flush();
    }


    private function hasGiggledBefore(User $giggler, Chuckle $chuckle): int
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.chuckle = :chuckle')
            ->andWhere('g.giggler = :giggler')
            ->setParameter('chuckle', $chuckle)
            ->setParameter('giggler', $giggler)
            ->select('COUNT(1)')
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }
}
