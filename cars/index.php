<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle('Вывод связанных полей');

use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
Loader::includeModule('iblock');

$iblockId = 16;
$iblockElementId = 35;

// Old API 
//$arFilter = ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'];
//$arSelect = ['ID', 'NAME', 'CODE', 'PROPERTY_MODEL', 'PROPERTY_CITY', 'PROPERTY_CREATE', 'PROPERTY_TEXT'];
//$res = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
//while($arFields = $res->fetch()){
//    #Debug::dump($arFields, "GET Element = ");
//    dump("GET Element = ".$arFields['ID'], $arFields);
//}


//$arFilter = ['IBLOCK_ID' => $iblockId];
//$arSelect = ['NAME'];
//$rsSect = CIBlockSection::GetList(['left_margin' => 'asc'], $arFilter, false, $arSelect, false);
//while ($arSect = $rsSect->fetch())
//{
//    dump($arSect);
//}


//$arElementProps = [
//    'MODEL' => 33,
//    'CREATE' => '2020-03-20',
//    'TEXT' => 'Текст элемента',
//];
//
//$arIblockFields = [
//    'IBLOCK_ID' => $iblockId,
//    'NAME' => 'New element',
//    'PROPERTY_VALUES' => $arElementProps
//];
//$objIblockElement = new \CIBlockElement();
//$objIblockElement->Add($arIblockFields);



// ORM

//get by id
$iblock = Iblock::wakeUp($iblockId);
$element = $iblock->getEntityDataClass()::getByPrimary($iblockElementId)->fetchObject();
dump("TEST 1", $element);
// get props
$element = $iblock->getEntityDataClass()::getByPrimary(
	$iblockElementId, 
	['select' => ['NAME', 'MODEL', 'CREATE', 'TEXT', 'CITY']])
->fetchObject();

dump("TEST 2", $element);
$name = $element->get('NAME');
echo 'NAME: ';
dump($name);

$model = $element->get('MODEL')->getValue();
echo 'MODEL: ';
dump([
    'MODEL' => $element->get('MODEL')->getValue(),
    'CREATE' => $element->get('CREATE')->getValue(),
    'TEXT' => $element->get('TEXT')->getValue(),
    'CITY' => $element->get('CITY')->getValue(),
]);


// get list
//$elements = \Bitrix\Iblock\Elements\ElementCarsTable::getList([ // car - cимвольный код API инфоблока
//    'select' => ['NAME', 'MODEL', 'CREATE', 'TEXT', 'CITY'], // имя свойства
//])->fetchCollection();
//
//foreach ($elements as $element) {
//    dump([
//        'MODEL' => $element->getModel()->getValue(),
//        'CREATE' => $element->getCreate()->getValue(),
//        'TEXT' => $element->getText()->getValue(),
//        'CITY' => $element->getCity()->getValue(),
//    ]);
//}

// получение через query списка элементов
$elements = \Bitrix\Iblock\Elements\ElementCarsTable::query() // car - cимвольный код API инфоблока
    ->addSelect('NAME')
    ->addSelect('MODEL') // имя свойства 
    ->addSelect('CREATE') // имя свойства
    ->addSelect('CITY') // имя свойства
    ->addSelect('TEXT') // имя свойства
    ->addSelect('ID')
    ->fetchCollection();

foreach ($elements as $key => $item) {
    //dump($item->getName().' '.$item->getModel()->getValue()); // получение значения свойства MODEL
    dump([
        'NAME' => $item->getName(),
        'MODEL' => $item->getModel()->getValue(),
        'CREATE' => $item->getCreate()->getValue(),
        'TEXT' => $item->getText()->getValue(),
        'CITY' => $item->getCity()->getValue(),
    ]);
     $value = $item->getModel()->getValue();
     if(!$value){
         $item->setModel(34); // изменение значения свойства MODEL
         $item->save(); // сохранение данных
         dump("Update ".$item->getId().' '.$item->getModel()->getValue().' TO 34');
     }
}


// Получить свойства инфоблока
/*$dbIblockProps = \Bitrix\Iblock\PropertyTable::getList(array(
    'select' => array('*'),
    'filter' => array('IBLOCK_ID' =>$iblockId)
));
while ($arIblockProps = $dbIblockProps->fetch()){
    pr($arIblockProps);
}*/

// Получить список элементов инфоблока
/*$dbItems = \Bitrix\Iblock\ElementTable::getList(array(
    'select' => array('ID', 'NAME', 'IBLOCK_ID'),
    'filter' => array('IBLOCK_ID' => 26)
));
$items = [];
while ($arItem = $dbItems->fetch()){
    $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID']
    );
    while($arProperty = $dbProperty->Fetch()){
        $arItem['PROPERTIES'][] = $arProperty;
    }

    $items [] = $arItem;
}
pr($items);*/