<?php

namespace tests\InFog;

use Respect\Config\Container;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $container;

    public function __construct()
    {
        $configFile = APP_CONFIG_DIR . '/config.ini.dist';
        // Configuration file
        if (is_file(APP_CONFIG_DIR . '/config_test.ini')) {
            $configFile = APP_CONFIG_DIR . '/config_test.ini';
        }
        $this->container = new Container($configFile);

        ini_set('display_errors', 1);
        error_reporting(-1);

        date_default_timezone_set($this->container->timezone);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}