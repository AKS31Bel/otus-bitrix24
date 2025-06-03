<?php

/**
 * @global $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('crm');

use Models\Lists\ZayavkiPropertyValuesTable as ZayavkiTable;

//$status = ZayavkiTable::update(74, ["PRICE" => "500|RUB"]);
$deal_id = 13;

$res = ZayavkiTable::getList([
    'select' => ['*', 'UF_*'],
    'filter' => ['DEAL' => $deal_id],
])->fetch();
dump($res);

//$status = \Bitrix\Crm\DealTable::update($deal_id, ["OPPORTUNITY" => "113.00"]);
//dump($status);

$arElements = \Bitrix\Crm\DealTable::getList([
    'select' => ['*', 'UF_*'],
    'filter' => ["ID" => $deal_id],
])->fetch();
dump('DealTable', $arElements);