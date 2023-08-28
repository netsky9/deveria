<?php

namespace App\Repository;

use App\Entity\Book;
use App\Form\BookFilterType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

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

    public function getAllWithAuthors(FormInterface $bookFilterForm = null)
    {
        $queryBuilder = $this->createQueryBuilder('b');

        $queryBuilder
            ->select('b.id', 'b.title', 'b.cover', 'b.publishAt', 'b.createdOn', 'b.modifiedOn', "GROUP_CONCAT(a.name SEPARATOR ', ') AS authors")
            ->leftJoin('b.authors', 'a')
            ->groupBy('b.id')
        ;

        if (isset($bookFilterForm)) {
            $data = $bookFilterForm->getData();

            if (isset($data['title'])) {
                $queryBuilder->andWhere($queryBuilder->expr()->like('b.title', ':title'))
                    ->setParameter('title', '%'.$data['title'].'%');
            }

            if (isset($data['publishAtFrom'])) {
                $queryBuilder->andWhere('b.publishAt >= :minValue')
                    ->setParameter('minValue', $data['publishAtFrom']);
            }

            if (isset($data['publishAtTo'])) {
                $queryBuilder->andWhere('b.publishAt <= :maxValue')
                    ->setParameter('maxValue', $data['publishAtTo']);
            }

            if (isset($data['author'])) {
                $queryBuilder->andHaving($queryBuilder->expr()->like('authors', ':authors'))
                    ->setParameter('authors', '%'.$data['author']->getName().'%');
            }
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
