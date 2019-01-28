<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
);

$loader->registerNamespaces(
    [
        'App\Forms' => APP_PATH .'/forms/',
        'App\Mail'  => APP_PATH . '/library/Mail/',
        'App\Acl'  => APP_PATH . '/library/Acl/',
    ]
);

$loader->registerFiles(
    [
        BASE_PATH . '/vendor/autoload.php'
    ]
);

$loader->register();
