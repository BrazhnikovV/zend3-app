<?php

namespace Application\Service;

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

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    /**
     * editTag - обновить Тег
     * @param $post
     * @param $data - данные формы создания поста
     * @return mixed
     */
    public function editTag($post, $data) {

        $this->em->persist($post);
        $this->em->flush();

        return $post;
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
