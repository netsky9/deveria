<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
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
    public function remove(Book $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getOneByid(int $id): ?Book
    {
        return $this->findOneBy(["id" => $id]);
    }

    public function getAllWithAuthors()
    {
        $queryBuilder = $this->createQueryBuilder('b');

        $queryBuilder
            ->select('b.id', 'b.title', 'b.cover', 'b.publishAt', 'b.createdOn', 'b.modifiedOn', "GROUP_CONCAT(a.name SEPARATOR ', ') AS authors")
            ->leftJoin('b.author', 'a')
            ->groupBy('b.id')
        ;

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
