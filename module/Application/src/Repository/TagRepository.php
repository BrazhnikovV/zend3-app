<?php

namespace Application\Repository;

use Application\Entity\Tag;
use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository
 * @package Application\Repository
 */
class TagRepository extends EntityRepository
{
    /**
     * findAllTags
     * @return \Doctrine\ORM\Query
     */
    public function findAllTags()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(Tag::class, 't')
            ->orderBy('t.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }
}
