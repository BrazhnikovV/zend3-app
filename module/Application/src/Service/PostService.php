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
     * @access private
     * @var Zend\Authentication\Authentication $authService - Auth service.
     */
    private $authService;

    /**
     * @access private
     * @var Application\Service\TagService $tagService - Tag service.
     */
    private $tagService;

    /**
     * @access private
     * @var Application\Filter\PostAddFilter $postEditFilter - Post filter.
     */
    private $postEditFilter;

    /**
     * @access private
     * @var Application\Filter\PostEditFilter $postAddFilter - Post filter.
     */
    private $postAddFilter;

    /**
     * Constructor.
     * @param $entityManager - менеджер сущностей
     * @param $authService - сервис аутентификации
     * @param $tagService - сервис тегов
     */
    public function __construct($entityManager, $authService, $tagService)
    {
        $this->em = $entityManager;

        $this->authService = $authService;
        $this->tagService  = $tagService;

        $this->postEditFilter = new PostEditFilter();
        $this->postAddFilter  = new PostAddFilter();
    }

    /**
     * getAllPosts - получить все посты вместе со связанными комментариями
     * @return mixed
     */
    public function getAllPosts() {
        return $this->em->getRepository(Post::class)->findAllPostsWithComments();
    }

    /**
     * getPostByIdWithAllTags - получить пост по id и все
     * существующие теги для списка выбора
     * @param $postId - идентификатор поста
     * @return array
     */
    public function getPostByIdWithAllTags($postId) {

        $arr = [];
        $arr["all_tags"] = $this->tagService->findAllTags();
        $arr["post_ent"] = $this->em->getRepository(Post::class)->find($postId);

        return $arr;
    }

    /**
     * addPost - создать новый пост
     * @param $formData - данные формы создания поста
     * @param $allTags - все теги
     * @return mixed
     */
    public function addPost($formData, $allTags) {

        // устанавливаем список всех тегов для фильтра, чтобы было с чем сравнивать
        $this->postAddFilter->setAllTags($allTags);
        $postEntity = $this->postAddFilter->filter($formData);
        $postEntity->setUser($this->getCurrentUser());

        $this->em->persist($postEntity);
        $this->em->flush();

        return $postEntity;
    }

    /**
     * editPost - обновить пост
     * @param $serviceData -
     * @param $formData - данные формы редактирования поста
     * @param $allTags - все теги
     * @return mixed
     */
    public function editPost($serviceData, $formData) {

        $postEnt = $serviceData["post_ent"];
        if ( $postEnt == null ) return false;

        // к сожалению интерфейс AbstractFilter не позволяет передавать в метод filter несколько переменных,
        // поэтому необходимо передавать либо составную структуру данных либо использовать сеттеры
        $this->postEditFilter->setAllTags($serviceData["all_tags"]);
        $this->postEditFilter->setFormData($formData);
        $postEnt = $this->postEditFilter->filter($postEnt);

        $this->em->persist($postEnt);
        $this->em->flush();

        return $postEnt;
    }

    /**
     * deletePost - удалить пост
     * @param $post - сущность поста
     * @return boolean
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
}
