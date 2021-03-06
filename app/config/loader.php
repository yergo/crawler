<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
		APPLICATION_PATH . $di->getConfig()->application->tasksDir,
    ), true
);


$loader->registerNamespaces(
	array(
		'Application\\Models' => APPLICATION_PATH . $config->application->modelsDir,
		'Application\\Models\\Entities' => APPLICATION_PATH . $config->application->modelsDir . 'entities/',
		'Application\\Models\\Services' => APPLICATION_PATH . $config->application->modelsDir . 'services/',
		'Application\\Models\\Services\\Trojmiasto' => APPLICATION_PATH . $config->application->modelsDir . 'services/trojmiasto',
		'Application\\Models\\Services\\Olx' => APPLICATION_PATH . $config->application->modelsDir . 'services/olx',
	), true
);

$loader->register();