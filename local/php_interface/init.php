<?php

use Bitrix\Main\EventManager;
define("REST_APAUTH_ALLOW_HTTP", true);

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
        'Otus\Diagnostic\Helper' => '/local/php_interface/classes/Otus/Diagnostic/Helper.php',
    ]
);



// Повесить событие на добавление HL записи
//\Bitrix\Main\EventManager::getInstance()->addEventHandler('', 'ColorsOnAdd', ['Otus\HL\Handlers', 'onColorAdd']);

EventManager::getInstance()->AddEventHandler('main', 'OnUserTypeBuildList', ['UserTypes\FormatTelegramLink', 'GetUserTypeDescription']);

//EventManager::getInstance()->AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\IPopupWrite', 'GetUserTypeDescription']);

EventManager::getInstance()->addEventHandler('crm', 'OnBeforeCrmDealUpdate', ['Events\DealHandler', 'CrmDealUpdate']);
// События Iblock сущности
//EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['Events\IblockHandler', 'OnBeforeIBlockUpdateHandler']);// перед изменением информационного блока.
EventManager::getInstance()->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['Events\IblockHandler', 'OnAfterIBlockElementUpdate']);// перед изменением информационного блока.
EventManager::getInstance()->addEventHandlerCompatible('rest', 'OnRestServiceBuildDescription', ['Rest\SmartPhone', 'OnRestServiceBuildDescriptionHandler']);  // для REST сервиса Смартфонов


\Bitrix\Main\UI\Extension::load([
    'timeman.custom',
    'koncept.create'
]);