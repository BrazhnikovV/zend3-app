<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\SessionManager;

/**
 * Class Module
 * @package Application - стартовый(начальный, основной хз..) модуль приложения
 */
class Module
{
    /**
     * @access public
     * @var string VERSION - версия модуля
     */
    const VERSION = '3.0.0dev';

    /**
     * getConfig - получить конфигурацию текущего модуля
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete.
     * @param MvcEvent $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get language settings from session.
        $sessionContainer = new Container('I18nSessionContainer', new SessionManager());

        $translator = $event->getApplication()->getServiceManager()->get('MvcTranslator');

        if (!isset($sessionContainer->languageId)) {
            $sessionContainer->languageId = $translator->getLocale();
        }

        $language = $event->getRequest()->getQuery("l");
        if ( $language !== null ) {
            $sessionContainer->languageId = $language;
        }

        $translator->setLocale($sessionContainer->languageId);
    }
}

