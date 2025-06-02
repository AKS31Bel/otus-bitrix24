<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/**
 * @var CMain $APPLICATION
 */

$APPLICATION->IncludeComponent(
	"otus:exchange.rate", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CURRENCY_FROM" => "UAH",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "600"
	),
	false,
	array(
		"HIDE_ICONS" => "N"
	)
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');