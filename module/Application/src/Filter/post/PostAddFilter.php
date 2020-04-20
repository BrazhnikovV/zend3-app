<?php

namespace Application\Filter\post;

use Application\Entity\Post;
use Zend\Filter\AbstractFilter;
use Application\common\TagsBindHelper;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostAddFilter
 * @package Application\post\Filter
 */
class PostAddFilter extends AbstractFilter
{
    /**
     * @access private
     * @var ArrayCollection $allTags - данные формы
     */
    private $allTags = [];

    /**
     * @inheritDoc
     */
    public function filter($value)
    {
        $post = new Post();
        $post->setTitle($value['title']);
        $post->setContent($value['content']);
        $post->setStatus($value['status']);
        $post->setTags(TagsBindHelper::getAttachedTags($value['tags'], $this->allTags));

        return $post;
    }

    /**
     * setAllTags
     * @param $tags
     */
    public function setAllTags($tags) {
        $this->allTags = $tags;
    }
}
