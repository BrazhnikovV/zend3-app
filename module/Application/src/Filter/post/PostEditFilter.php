<?php

namespace Application\Filter\post;

use Zend\Filter\AbstractFilter;
use Application\common\TagsBindHelper;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostEditFilter
 * @package Application\post\Filter
 */
class PostEditFilter extends AbstractFilter
{
    /**
     * @access private
     * @var ArrayCollection $allTags - данные формы
     */
    private $allTags = [];

    /**
     * @access private
     * @var array - данные формы
     */
    private $formData;


    /**
     * @inheritDoc
     */
    public function filter($value)
    {
        $value->setTitle($this->formData['title']);
        $value->setContent($this->formData['content']);
        $value->setStatus($this->formData['status']);
        $value->setTags(
            TagsBindHelper::getAttachedTags($this->formData['tags'], $this->allTags)
        );

        return $value;
    }

    /**
     * setAllTags
     * @param $tags
     */
    public function setAllTags($tags) {
        $this->allTags = $tags;
    }

    /**
     * setFormData
     * @param $data
     */
    public function setFormData($data) {
        $this->formData = $data;
    }
}
