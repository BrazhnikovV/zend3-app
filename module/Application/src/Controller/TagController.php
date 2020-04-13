<?php

namespace Application\Controller;

use Application\Entity\Tag;
use Application\Form\TagForm;
use Zend\View\Model\ViewModel;
use Common\Filter\PaginatorFilter;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class TagController extends AbstractActionController
{
    /**
     * @access private
     * @var Doctrine\ORM\EntityManager $em - Entity manager.
     */
    private $em;


    /**
     * @access private
     * @var Application\Service\TagService $tagService - Servise tag.
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
     * @param $tagService - сервис тегов
     */
    public function __construct($entityManager, $tagService)
    {
        $this->em = $entityManager;
        $this->tagService = $tagService;
        $this->paginatorFilter = new PaginatorFilter();
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * index tags page.
     */
    public function indexAction()
    {
        $currentPage = $this->params()->fromQuery('page', 1);
        $selectQuery = $this->em->getRepository(Tag::class)->findAllTags();
        $paginator   = $this->paginatorFilter->filter($selectQuery);
        $paginator->setCurrentPageNumber($currentPage);

        return new ViewModel(['tags' => $paginator]);
    }

    /**
     * This action displays a page allowing to add a new tag.
     */
    public function addAction()
    {
        $form = new TagForm('create', $this->em);

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
     * The "edit" action displays a page allowing to edit tag.
     */
    public function editAction()
    {
        $tagEntity = $this->checkInputDataIdAndEntity();
        if ( $tagEntity === false ) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new TagForm('update', $this->em, $tagEntity);

        if ( $this->getRequest()->isPost() ) {

            $data = $this->params()->fromPost();
            $form->setData($data);

            if( $form->isValid() ) {

                $data = $form->getData();
                $this->tagService->editTag($tagEntity, $data);

                return $this->redirect()->toRoute('tags', ['action'=>'index']);
            }
        } else {
            $form->setData(array( 'name' => $tagEntity->getName()));
        }

        return new ViewModel(
            array('tag' => $tagEntity, 'form' => $form)
        );
    }

    /**
     * This action deletes a tags.
     */
    public function deleteAction()
    {
        $post = $this->checkInputDataIdAndEntity();
        if ( $post === false ) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->tagService->deleteTag($post);
        $this->flashMessenger()->addSuccessMessage('Deleted the tag.');

        return $this->redirect()->toRoute('tags', ['action'=>'index']);
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

