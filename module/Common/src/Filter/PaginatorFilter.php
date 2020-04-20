<?php

namespace Common\Filter;

use Zend\Paginator\Paginator;
use Zend\Filter\AbstractFilter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

/**
 * Class PaginatorFilter
 * @package Common\Filter
 */
class PaginatorFilter extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function filter($value)
    {
        $adapter   = new DoctrineAdapter(new ORMPaginator($value, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);

        return $paginator;
    }
}
