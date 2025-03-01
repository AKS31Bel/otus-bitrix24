<?php

foreach ([
            __DIR__.'/../vendor/autoload.php',
            __DIR__.'/../app/autoload.php',
             __DIR__.'/classes/Otus/autoload.php',
         ] as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

//if(file_exists(__DIR__.'/classes/Otus/autoload.php')){
//    require_once __DIR__.'/classes/Otus/autoload.php';
//}
//
//if(file_exists(__DIR__.'/../vendor/autoload.php')){
//    require_once __DIR__.'/../vendor/autoload.php';
//}
