<?php
namespace Application\Service\Factory;

use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use User\Service\RbacManager;
use Zend\Session\SessionManager;
use Application\Service\NavManager;
use Psr\Container\ContainerInterface;

/**
 * Class NavManagerFactory - This is the factory class for NavManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 * @package Application\Service\Factory
 */
class NavManagerFactory
{
    /**
     * This method creates the NavManager service and returns its instance.
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return NavManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);

        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $translate = $viewHelperManager->get('translate');
        $rbacManager = $container->get(RbacManager::class);

        // Получить настройки языка из сессии.
        // Раньше вызов контейнера сессии производился вот таким образом => $containerI18n = $container->get('I18nSessionContainer');
        // =========================================================================================================================
        // Но такой подходи при обращению к контейнеру сессий приводит к старту сессии, что дает сбой при запуске тестов
        $sessionContainer = new Container('I18nSessionContainer', new SessionManager());

        return new NavManager($authService, $urlHelper, $rbacManager, $sessionContainer, $translate);
    }
}
