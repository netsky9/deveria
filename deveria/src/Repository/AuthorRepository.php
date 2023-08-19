<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Author $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Author $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAllBy(array $criteria = [])
    {
        if (!empty($criteria)) {
            return $this->findBy($criteria);
        }

        return $this->findAll();
    }

    public function getAll()
    {
        return $this->findAll();
    }

    public function getOneByid(int $id): ?Author
    {
        return $this->findOneBy(["id" => $id]);
    }

    public function getAllWithBooks()
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $queryBuilder
            ->select('a.id', 'a.name', 'a.description', 'a.modifiedOn', 'a.createdOn', "GROUP_CONCAT(b.title SEPARATOR ', ') AS books")
            ->leftJoin('a.books', 'b')
            ->groupBy('a.id')
        ;

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
