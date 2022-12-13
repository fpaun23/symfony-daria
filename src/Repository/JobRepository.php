<?php

namespace App\Repository;

use App\Entity\Jobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jobs>
 *
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    public function save(Jobs $entity): bool
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity -> getId() > 0;
    }

    public function remove(Jobs $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $params
     * @return int
     */
    public function update(int $id, array $params): int
    {
        $queryBuilder = $this ->createQueryBuilder('j');

        $nbUpdatedRows = $queryBuilder ->update()
            ->set('j.name', ':jobName')
            //->set('j.description', ':jobDescription')
            ->where('j.id = :jobId')
            ->setParameter('jobName', $params['name'])
            //->setParameter('jobDescription', $params['description'])
            ->setParameter('jobId', $id)
            ->getQuery()
            ->execute();

            return $nbUpdatedRows;
    }
    /**
     * delete
     *
     * @param  mixed $id
     * @return int
     */
    public function delete(int $id): int
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $nbUpdatedRows = $queryBuilder ->delete()
            ->delete('Jobs', 'j')
            ->where('j.id = :jobId')
            ->setParameter('jobId', $id)
            ->getQuery()
            ->execute();

            return $nbUpdatedRows;
    }
    /**
     * listJob
     *
     * @return array
     */
    public function listJob(): array
    {
        $queryBuilder = $this->createQueryBuilder('j');

        $job = $queryBuilder
            ->getQuery()
            ->getArrayResult();

        return $job;
    }
    /**
     * getJobId
     *
     * @param  mixed $id
     * @return array
     */
    public function getJobId(int $id): array
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $job = $queryBuilder
            ->select('j.name')
            ->where("j.id = $id")
            ->getQuery()
            ->execute();

        return $job;
    }
    /**
     * getJobName
     *
     * @param  mixed $name
     * @return array
     */
    public function getJobName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $job = $queryBuilder
        ->select('j.name')
        ->where("j.name = :jobName")
        ->setParameter('jobName', $name)
        ->getQuery()
        ->execute();

        return $job;
    }
    /**
     * getJobLike
     *
     * @param  mixed $name
     * @return array
     */
    public function getJobLike(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $job = $queryBuilder
        ->select('j.id, j.name')
        ->where("j.name LIKE :name")
        ->setParameter('name', '%' . $name . '%')
        ->getQuery()
        ->execute();

        return $job;
    }

//    /**
//     * @return Jobs[] Returns an array of Jobs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jobs
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
