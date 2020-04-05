<?php

namespace Application\Filter\post;

use Application\Entity\Post;
use Zend\Filter\AbstractFilter;

/**
 * Class PostAddFilter
 * @package Application\post\Filter
 */
class PostAddFilter extends AbstractFilter
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
        $post = new Post();
        $post->setTitle($value['title']);
        $post->setContent($value['content']);
        $post->setStatus($value['status']);
        $post->setDateCreated(date('Y-m-d H:i:s'));

        return $post;
    }
}
