<?php

namespace Application\Service\Factory;

use Application\Service\PostService;
use Application\Service\TagService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PostServiceFactory
 * @package Application\Service\Factory
 */
class PostServiceFactory implements FactoryInterface
{
    /**
     * __invoke - инстанцирование объекта
     * @param ContainerInterface $container - менеджер сервисов
     * @param $requestedName - запрашиваемое имя ...
     * @param array|null $options - дополнительные опции
     * @return PostService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService   = $container->get(\Zend\Authentication\AuthenticationService::class);
        $tagService    = $container->get(TagService::class);

        return new PostService($entityManager, $authService, $tagService);
    }
}
