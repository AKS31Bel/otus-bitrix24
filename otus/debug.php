<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle("Отладка Битрикса ДЗ");

$info = [
    "SERVER" => $_SERVER,
    "COOKIE" => $_COOKIE,
];

Otus\Diagnostic\Helper::writeToLog($info, "Info");
Bitrix\Main\Diag\Debug::writeToFile($info, 'Info', '/logs/otus_my.log');
echo "OK";

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';