<?php

namespace Application\Controller;

use Application\Entity\Tag;
use Application\Form\TagForm;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Common\Filter\PaginatorFilter;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class TagController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\em
     */
    private $em;


    /**
     * Servise tag.
     * @var Application\Service\TagService
     */
    private $tagService;

    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     * @param $entityManager - менеджер сущностей
     * @param $tagService - сервис тегов
     */
    public function __construct($entityManager, $tagService)
    {
       $this->em = $entityManager;
       $this->tagService = $tagService;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $currentPage = $this->params()->fromQuery('page', 1);
        $selectQuery = $this->em->getRepository(Tag::class)->findAllTags();
        $paginator   = PaginatorFilter::get($selectQuery);
        $paginator->setCurrentPageNumber($currentPage);

        return new ViewModel([
            'tags' => $paginator
        ]);
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {
        $form = new TagForm('create');

        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            if( $form->isValid() ) {

                $data = $form->getData();
                $this->tagService->addTag($data);

                return $this->redirect()->toRoute('tags', ['action'=>'index']);
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
    public function editAction()
    {
        $post = $this->checkInputDataIdAndEntity();
        if ( $post === false ) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new TagForm('update');

        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();
            $form->setData($data);

            if( $form->isValid() ) {

                $data = $form->getData();
                $this->tagService->editTag($post, $data);

                return $this->redirect()->toRoute('tags', ['action'=>'index']);
            }
        } else {
            $form->setData(array( 'name' => $post->getName()));
        }

        return new ViewModel(
            array('tag' => $post, 'form' => $form)
        );
    }

    /**
     * This action deletes a permission.
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

        $post = $this->em->getRepository(Tag::class)->find($id);
        if ( $post == null ){
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        return $post;
    }
}

