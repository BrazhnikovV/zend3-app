<?php

namespace Application\Service;

use User\Entity\User;
use Application\Entity\Post;
use Application\Filter\post\PostAddFilter;
use Application\Filter\post\PostEditFilter;
use Doctrine\Common\Collections\ArrayCollection;

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
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;

    /**
     * Constructor.
     * @param $entityManager - менеджер сущностей
     * @param $authService
     */
    public function __construct($entityManager, $authService)
    {
        $this->em = $entityManager;
        $this->authService = $authService;
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
     * @param $formData - данные формы создания поста
     * @param $allTags - все теги
     * @return mixed
     */
    public function addPost($formData, $allTags) {

        $selTags = $formData["tags"];

        $postEntity = PostAddFilter::get($formData);
        $postEntity->setUser($this->getCurrentUser());
        $postEntity->setTags($this->getAttachedTags($selTags, $allTags));

        $this->em->persist($postEntity);
        $this->em->flush();

        return $postEntity;
    }

    /**
     * editPost - обновить пост
     * @param $postEntity - сущность поста(полученная по id)
     * @param $formData - данные формы редактирования поста
     * @param $allTags - все теги
     * @return mixed
     */
    public function editPost($postEntity, $formData, $allTags) {

        $selTags = $formData["tags"];

        PostEditFilter::setFormData($formData);
        $postEntity = PostEditFilter::get($postEntity);
        $postEntity->setTags($this->getAttachedTags($selTags, $allTags));

        $this->em->persist($postEntity);
        $this->em->flush();

        return $postEntity;
    }

    /**
     * deletePost - удалить пост
     * @param $post
     * @param $data - данные формы создания поста
     * @return mixed
     */
    public function deletePost($post) {

        $this->em->remove($post);
        $this->em->flush();

        return true;
    }

    /**
     * getCurrentUser - получить авторизованного пользователя
     * @return mixed
     */
    private function getCurrentUser() {
        $email = $this->authService->getIdentity();
        return $this->em->getRepository(User::class)->findOneByEmail($email);
    }

    /**
     * getAttachedTags
     * @param $selectedTags
     * @param $allTags
     * @return array|ArrayCollection
     */
    private function getAttachedTags($selectedTags, $allTags) {

        $tags = new ArrayCollection();

        foreach ( $allTags as $tag ) {
            foreach ( $selectedTags as $selectTag ) {
                if ((int)$selectTag == $tag->getId() ) {
                    $tags[] = $tag;
                }
            }
        }

        return $tags;
    }
}
