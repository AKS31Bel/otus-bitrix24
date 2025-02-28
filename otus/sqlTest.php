<?php

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle("Отладка SQL");

Loader::includeModule("iblock");

Application::getConnection()->startTracker();
$queryResult = Bitrix\Iblock\ElementTable::getList([
    'filter' => [
        'IBLOCK_ID' => 5,
    ],
    'select' => [
        'ID',
        'NAME',
    ]
]);
Application::getConnection()->stopTracker();
Debug::dump($queryResult->getTrackerQuery()->getSql());


require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';