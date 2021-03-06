<?php

namespace Application\Controller;

use Application\Entity\Post;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Common\Filter\PaginatorFilter;
use Zend\Mvc\Controller\AbstractActionController;


/**
 * Class PostController - This is the main controller class of the Post application. It contains
 * site-wide actions such as Home, Add, Edit, Delete.
 * @package Application\Controller
 */
class PostController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\em
     */
    private $em;


    /**
     * Servise  post.
     * @var Application\Service\PostService
     */
    private $postService;

    /**
     * Servise  tag.
     * @var Application\Service\TagService
     */
    private $tagService;

    /**
     * @access private
     * @var Common\Filter\PaginatorFilter $paginatorFilter - filter .
     */
    private $paginatorFilter;

    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     * @param $entityManager - менеджер сущностей
     * @param $postService - сервис постов
     * @param $tagService - сервис тегов
     */
    public function __construct($entityManager, $postService, $tagService)
    {
        $this->em = $entityManager;
        $this->postService = $postService;
        $this->tagService  = $tagService;
        $this->paginatorFilter = new PaginatorFilter();
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $currentPage = $this->params()->fromQuery('page', 1);
        $selectQuery = $this->em->getRepository(Post::class)->findAllPosts();
        $paginator   = $this->paginatorFilter->filter($selectQuery);
        $paginator->setCurrentPageNumber($currentPage);

        return new ViewModel(['posts' => $paginator]);
    }

    /**
     * This action displays a page allowing to add a new post.
     */
    public function addAction()
    {
        $form = new PostForm('create', $this->em);
        $tags = $this->tagService->findAllTags();

        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            if( $form->isValid() ) {

                $this->postService->addPost($data, $tags);
                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        }

        return new ViewModel(['form' => $form, 'tags' => $tags]);
    }

    /**
     * The "edit" action displays a page allowing to edit post.
     */
    public function editAction()
    {
        $postEnt = $this->checkInputDataIdAndEntity();
        if ( $postEnt === false ) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new PostForm('update', $this->em, $postEnt);
        $serviceData = $this->postService->getPostByIdWithAllTags($postEnt->getId());

        if ( $this->getRequest()->isPost() ) {

            $postData = $this->params()->fromPost();
            $form->setData($postData);
            if( $form->isValid() ) {
                $this->postService->editPost($serviceData, $postData);
                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        } else {

            $form->setData(array(
                'title'   => $postEnt->getTitle(),
                'content' => $postEnt->getContent(),
                'status'  => $postEnt->getStatus()
            ));
        }

        return new ViewModel(array('data' => $serviceData, 'form' => $form));
    }

    /**
     * This action deletes a post.
     */
    public function deleteAction()
    {
        $post = $this->checkInputDataIdAndEntity();
        if ( $post === false ) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->postService->deletePost($post);
        $this->flashMessenger()->addSuccessMessage('Deleted the post.');

        return $this->redirect()->toRoute('posts', ['action'=>'index']);
    }

    /**
     * checkEditInputData
     * @return array
     */
    private function checkInputDataIdAndEntity() {

        $id = (int)$this->params()->fromRoute('id', -1);
        if ( $id < 1 ) {
            return false;
        }

        $post = $this->em->getRepository(Post::class)->find($id);
        if ( $post == null ){
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        return $post;
    }
}

