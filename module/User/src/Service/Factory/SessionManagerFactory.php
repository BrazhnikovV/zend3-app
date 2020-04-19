<?php

namespace User\Service\Factory;

use Zend\Session\SessionManager;
use Psr\Container\ContainerInterface;
use Zend\Session\Config\SessionConfig;

class SessionManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = $config['session'];

        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);

        // You could also configure the storage adapter, save handler, and
        // validators here and pass them to the session manager constructor,
        // if desired.
        return new SessionManager($sessionConfig);
    }
}
