<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Escaper;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use MyApp\Locale;
use Phalcon\Cache\Adapter\Stream as streamcache;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Http\Response\Cookies;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

include_once "../vendor/autoload.php";

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
        APP_PATH . "/messages/",
    ]
);
$loader->registerNamespaces(
    [
        'MyApp\handle' => APP_PATH . '/handler/',
        'MyApp' => APP_PATH . "/locals/"
    ]
);
$loader->registerClasses(
    [
        'Listner'   => APP_PATH . '/handler/Listner.php',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'newdb',
            ]
        );
    }
);

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();
        return $session;
    }
);

$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();

        $cookies->useEncryption(false);

        return $cookies;
    }
);

$container->set(
    'cache',
    function () {
        $serializerFactory = new SerializerFactory();
        $options = [
            'defaultSerializer' => 'Json',
            'lifetime'          => 7200,
            'storageDir'        => APP_PATH . '/data/storage/cache',
        ];
        return new streamcache($serializerFactory, $options);
    }
);

$container->set('locale', (new Locale())->getTranslator());

$application = new Application($container);

$eventsManager = $container->get('eventsManager');
$eventsManager->attach(
    'application:beforeHandleRequest',
    new Listner()
);
$container->set(
    'EventsManager',
    $eventsManager
);


$application->setEventsManager($eventsManager);
$application = new Application($container);
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
