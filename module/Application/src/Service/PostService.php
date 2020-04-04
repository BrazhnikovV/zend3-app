<?php

namespace Application\Service;

use Application\Entity\Post;

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
     * getAllPosts - получить все посты вместе со связанными комментариями
     * @return mixed
     */
    public function addPost($data) {

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $post->setDateCreated(date('Y-m-d H:i:s'));

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }
}
