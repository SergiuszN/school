<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLandingKnp(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'pc')
            ->andWhere('p.isActive = 1')
            ->andWhere('pc.isActive = 1')
            ->orderBy('p.created', 'DESC');
    }

    public function findLandingKnpWithCategory(PostCategory $category): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = 1')
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.created', 'DESC');
    }

    public function findLatest(): array
    {
        return $this->findLandingKnp()
            ->setMaxResults(3)
            ->getQuery()
            ->execute();
    }
}
