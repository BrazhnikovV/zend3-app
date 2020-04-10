<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository
 * @package Application\Repository
 */
class TagRepository extends EntityRepository
{
    /**
     * zaglushka
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function zaglushka()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        return $queryBuilder;
    }
}
