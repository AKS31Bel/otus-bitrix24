<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arActivityDescription = [
    "NAME" => Loc::getMessage("INN_DESCR_NAME"),
    "DESCRIPTION" => Loc::getMessage("INN_DESCR_DESCR"),
    "TYPE" => "activity",
    "CLASS" => "GetInnActivity",
    "JSCLASS" => "BizProcActivity",
    "CATEGORY" => [
        "ID" => "other",
    ],
    "RETURN" => [
        "CompanyName" => [
            "NAME" => Loc::getMessage("INN_DESCR_FIELD_TEXT"),
            "TYPE" => "string",
        ],
    ],
];