<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle('Таблица смартфонов');

use Models\SmartphoneTable as Phones;
use Models\Lists\SmartPhoneScreenPropertyValuesTable as ScreenPropertyValues;
use Models\Lists\SmartPhoneBatteryPropertyValuesTable as BatteryPropertyValues;

$phones = Phones::getList([
    'select' => [
        'id',
        'name',
        'screen_id',
        'battery_id',
        'SCREEN_NAME'=>'SCREEN.ELEMENT.NAME',
        'BATTERY_NAME'=>'BATTERY.ELEMENT.NAME',
    ],
    'order' => ['name' => 'ASC'],
])->fetchAll();

echo "Таблица смартфонов:";
dump($phones);

$screens = ScreenPropertyValues::getList([
    'select' => [
        'ID'=>'IBLOCK_ELEMENT_ID',
        'NAME'=>'ELEMENT.NAME',
        'SMARTPHONE_ID'=>'SMARTPHONE.ID',
        'SMARTPHONE_NAME'=>'SMARTPHONE.NAME',
        'SMARTPHONE_BATTERY_ID'=>'SMARTPHONE.BATTERY_ID',
        'SMARTPHONE_BATTERY_NAME'=>'SMARTPHONE.BATTERY.ELEMENT.NAME',
    ],
])->fetchAll();

echo "Таблица Экранов с выводом смартфонов:";
dump($screens);

$batteryes = BatteryPropertyValues::getList([
    'select' => [
        'ID'=>'IBLOCK_ELEMENT_ID',
        'NAME'=>'ELEMENT.NAME',
        'SMARTPHONE_ID'=>'SMARTPHONE.ID',
        'SMARTPHONE_NAME'=>'SMARTPHONE.NAME',
        'SMARTPHONE_BATTERY_ID'=>'SMARTPHONE.BATTERY_ID',
        'SMARTPHONE_BATTERY_NAME'=>'SMARTPHONE.BATTERY.ELEMENT.NAME',
    ],
])->fetchAll();

echo "Таблица Батареек с выводом смартфонов:";
dump($batteryes);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");