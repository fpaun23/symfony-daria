<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    /**
     * __construct
     *
     * @param  mixed $registry
     * @return void
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }
    /**
     * save a new company each time
     *
     * @param  mixed $entity
     * @return void
     */
    public function save(Company $entity): bool
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity -> getId() > 0;
    }
    /**
     * removes a company
     *
     * @param  mixed $entity
     * @return void
     */
    public function remove(Company $entity): void
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
    public function updateCompany(int $id, array $params): int
    {
        $queryBuilder = $this ->createQueryBuilder('c');

        $nbUpdatedRows = $queryBuilder ->update()
            ->set('c.name', ':companyName')
            ->where('c.id = :companyId')
            ->setParameter('companyName', $params['name'])
            ->setParameter('companyId', $id)
            ->getQuery()
            ->execute();

            return $nbUpdatedRows;
    }
    /**
     * listCompany
     *
     * @return array
     */
    public function listCompany(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        $company = $queryBuilder
            ->getQuery()
            ->getArrayResult();

        return $company;
    }
    /**
     * getCompanyId
     *
     * @param  mixed $id
     * @return array
     */
    public function getCompanyId(int $id): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $company = $queryBuilder
            ->select('c.name')
            ->where("c.id = $id")
            ->getQuery()
            ->execute();

        return $company;
    }
    /**
     * getCompanyName
     *
     * @param  mixed $name
     * @return array
     */
    public function getCompanyName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $company = $queryBuilder
        ->select('c.name')
        ->where("c.name = :companyName")
        ->setParameter('companyName', $name)
        ->getQuery()
        ->execute();

        return $company;
    }
    /**
     * getCompanyLike
     *
     * @param  mixed $name
     * @return array
     */
    public function getCompanyLike(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $company = $queryBuilder
        ->select('c.id, c.name')
        ->where("c.name LIKE :name")
        ->setParameter('name', '%' . $name . '%')
        ->getQuery()
        ->execute();

        return $company;
    }
//    public function findOneBySomeField($value): ?Company
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
