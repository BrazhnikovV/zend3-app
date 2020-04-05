<?php

namespace Application\Filter\post;

use Zend\Filter\AbstractFilter;

/**
 * Class PostEditFilter
 * @package Application\post\Filter
 */
class PostEditFilter extends AbstractFilter
{
    /**
     * @access private
     * @var Application\Form\PostForm $formData - данные формы
     */
    static private $formData;

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
        $value->setTitle(self::$formData['title']);
        $value->setContent(self::$formData['content']);
        $value->setStatus(self::$formData['status']);

        return $value;
    }

    /**
     * setFormData
     * @param $data
     */
    static public function setFormData($data) {
        self::$formData = $data;
    }
}
