<?php
namespace Application\Controller\Factory;

use Application\Service\PostService;
use Interop\Container\ContainerInterface;
use Application\Controller\PostController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class PostControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postService = $container->get(PostService::class);
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        return new PostController($entityManager, $postService, $authService);
    }
}
