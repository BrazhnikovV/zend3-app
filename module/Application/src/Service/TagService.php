<?php

namespace Application\Service;

use Application\Entity\Tag;

/**
 * Class TagService
 * @package Application\Service
 */
class TagService
{
    /**
     * @access private
     * @var Doctrine\ORM\EntityManager $em менеджер сущностей
     */
    private $em;

    /**
     * Constructor.
     * @param $entityManager - менеджер сущностей
     * @param $authService
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * addTag - создать новый тег
     * @param $data - данные формы создания поста
     * @return mixed
     */
    public function addTag($data) {

        $tag = new Tag();
        $tag->setName($data['name']);
        $this->em->persist($tag);
        $this->em->flush();

        return $tag;
    }

    /**
     * editTag - обновить Тег
     * @param $tag
     * @param $data - данные формы создания тега
     * @return mixed
     */
    public function editTag($tag, $data) {

        $tag->setName($data["name"]);
        $this->em->persist($tag);
        $this->em->flush();

        return $tag;
    }

    /**
     * deleteTag - удалить тег
     * @param $post
     * @param $data - данные формы создания тега
     * @return mixed
     */
    public function deleteTag($post) {

        $this->em->remove($post);
        $this->em->flush();

        return true;
    }
}
