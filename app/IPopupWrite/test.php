<?php

/**
 * @global $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
\Bitrix\Main\Loader::includeModule('iblock');

$date = '2024-04-01 12:15:00';
$count = \Models\Lists\BronPropertyValuesTable::getList([
    "select" => ["*"],
    "filter" => ['VREMYA' => $date],
    'count_total' => true,
])->fetchAll();
dump($count);

$res = \Bitrix\Iblock\Elements\ElementZayavkiTable::getList([
    'select' => ['*'],
    //'filter' => ['DEAL' => 13]
])->fetch();
dump($res);