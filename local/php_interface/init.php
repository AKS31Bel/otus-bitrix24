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

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    [
        'Models\\SmartPhoneTable' => '/local/app/Models/SmartPhoneTable.php',
    ]
);


// Повесить событие на добавление HL записи
//\Bitrix\Main\EventManager::getInstance()->addEventHandler('', 'ColorsOnAdd', ['Otus\HL\Handlers', 'onColorAdd']);
