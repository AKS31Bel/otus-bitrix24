<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/**
 * @var CMain $APPLICATION
 */

$APPLICATION->IncludeComponent(
    "otus:exchange.rate",
    ".default",
    [
        "COMPONENT_TEMPLATE" => ".default",
        "CURRENCY_FROM" => "USD",
    ],
    false,
    [
        "HIDE_ICONS" => "N",
    ]
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');