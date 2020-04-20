<?php

namespace Application\Filter\post;

use Application\Entity\Post;
use Zend\Filter\AbstractFilter;

/**
 * Class PostServiceFilter
 * @package Application\post\Filter
 */
class PostServiceFilter extends AbstractFilter
{
    /**
     * @inheritDoc
     */
    public function filter($value)
    {

        return $value;
    }
}
