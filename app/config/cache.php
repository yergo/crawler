<?php

$di->setShared('cache', function () {

    $frontCache = new \Phalcon\Cache\Frontend\Data([
        'lifetime' => 120
    ]);

    $cache = new \Phalcon\Cache\Backend\Apc($frontCache, array(
        'prefix' => 'crawler-data-',
    ));

    return $cache;
});
