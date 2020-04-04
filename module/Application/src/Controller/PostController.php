<?php
namespace Application\Controller;

use Application\Entity\Post;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

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
     */
    public function __construct($entityManager, $postService)
    {
       $this->entityManager = $entityManager;
       $this->postService = $postService;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $query = $this->entityManager->getRepository(Post::class)->findAllPosts();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'posts' => $paginator
        ]);
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {
        $form = new PostForm('create', $this->entityManager);

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {

                $data = $form->getData();
                $this->postService->addPost($data);

                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }
}

