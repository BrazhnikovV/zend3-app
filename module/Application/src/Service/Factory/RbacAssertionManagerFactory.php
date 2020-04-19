<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\RbacAssertionManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class RbacAssertionManagerFactory - This is the factory class for RbacAssertionManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 * @package Application\Service\Factory
 */
class RbacAssertionManagerFactory
{
    /**
     * This method creates the NavManager service and returns its instance.
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return RbacAssertionManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService   = $container->get(AuthenticationService::class);

        return new RbacAssertionManager($entityManager, $authService);
    }
}
