<?php

use \Psr\Container\ContainerInterface;

return function (ContainerInterface $container){
    $container->set('settings', function(){
        return [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
            'dbHost' => 'mysql31.1blu.de',
            'dbUser' => 's86635_3357939',
            'dbPass' => 'GnAoIN@K#&E!ba0',
            'dbName' => 'db86635x3357939'
        ];
    });
};