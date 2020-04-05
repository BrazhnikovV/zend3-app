<?php

namespace Application\Controller;

use Application\Entity\Post;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Common\Filter\PaginatorFilter;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class PostController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Entity manager.
     * @var Application\Service\PostService
     */
    private $postService;

    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     * @param $entityManager - менеджер сущностей
     * @param $postService - сервис постов
     */
    public function __construct($entityManager, $postService)
    {
       $this->entityManager = $entityManager;
       $this->postService   = $postService;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $currentPage = $this->params()->fromQuery('page', 1);
        $selectQuery = $this->entityManager->getRepository(Post::class)->findAllPosts();
        $paginator   = PaginatorFilter::get($selectQuery);
        $paginator->setCurrentPageNumber($currentPage);

        return new ViewModel(['posts' => $paginator]);
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {
        $form = new PostForm('create', $this->entityManager);

        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();
            $form->setData($data);

            if( $form->isValid() ) {

                $data = $form->getData();
                $this->postService->addPost($data);

                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ( $id < 1 )
            $this->getResponse()->setStatusCode(404);

        $post = $this->entityManager->getRepository(Post::class)->find($id);
        if ( $post == null )
            $this->getResponse()->setStatusCode(404);

        $form = new PostForm('update', $this->entityManager, $post);
        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                // Update post
                $this->postService->editPost($post, $data);

                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        } else {

            $form->setData(array(
                'title'   => $post->getTitle(),
                'content' => $post->getContent(),
                'status'  => $post->getStatus()
            ));
        }

        return new ViewModel(
            array('post' => $post, 'form' => $form)
        );
    }
}

