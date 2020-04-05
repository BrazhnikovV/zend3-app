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
     * @access public
     * @param $value - фильтруемые данные
     * @return mixed
     */
    static public function get($value)
    {
        return self::filter($value);
    }

    /**
     * @inheritDoc
     */
    public function filter($value)
    {
        $adapter   = new DoctrineAdapter(new ORMPaginator($value, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(3);

        return $paginator;
    }
}
