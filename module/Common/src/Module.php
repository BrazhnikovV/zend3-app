<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Common;

/**
 * Class Module - модуль с общеиспользуемыми элементами архитектуры и поведения
 * @package Common
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
}

