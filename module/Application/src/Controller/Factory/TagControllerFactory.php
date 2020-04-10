<?php
namespace Application\Controller\Factory;

use Application\Service\TagService;
use Application\Controller\TagController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for TagController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TagControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tagService = $container->get(TagService::class);
        return new TagController($entityManager, $tagService);
    }
}
