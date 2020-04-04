<?php

namespace Application\Repository;

use Application\Entity\Post;
use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository
 * @package Application\Repository
 */
class PostRepository extends EntityRepository
{
    /**
     * findAllPostsWithComments - найти все посты вместе с комментариями
     * @return array|mixed
     */
    public function findAllPostsWithComments()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('p')
                     ->addSelect('c')
                     ->from(Post::class, 'p')
                     ->leftJoin('p.comments', 'c');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
