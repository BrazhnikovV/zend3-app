<?php

namespace Application\Service;

use Application\Entity\Post;
use Application\Filter\PostAddFilter;

/**
 * Class PostService
 * @package Application\Service
 */
class PostService
{
    /**
     * @access private
     * @var Doctrine\ORM\EntityManager $em менеджер сущностей
     */
    private $em;

    /**
     * Constructor.
     * @param $entityManager - менеджер сущностей
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * getAllPosts - получить все посты вместе со связанными комментариями
     * @return mixed
     */
    public function getAllPosts() {
        return $this->em->getRepository(Post::class)->findAllPostsWithComments();
    }

    /**
     * addPost - создать новый пост
     * @param $data - данные формы создания поста
     * @return mixed
     */
    public function addPost($data) {

        $post = PostAddFilter::get($data);

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }
}
