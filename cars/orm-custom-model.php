<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle('Вывод связанных полей');

use Models\Lists\CarsPropertyValuesTable as CarsTable;

// вывод данных по списку записей из инфоблока Автомобили
$cars = CarsTable::getList([
		'select'=>[
            'ID'=>'IBLOCK_ELEMENT_ID',
            'NAME'=>'ELEMENT.NAME',
            'CITY_ID'=>'CITY_ID',
            'CITY_NAME'=>'CITY.ELEMENT.NAME',
            'MODEL_NAME'=>'MODEL.ELEMENT.NAME',
            'MODEL_ID'=>'MODEL_ID',
            'CREATE' => 'CREATE',
            'TEXT' => 'TEXT',
        ]
  ])->fetchAll();

dump($cars);

//$cars = CarsTable::query()
//    ->setSelect([
//        'NAME' => 'ELEMENT.NAME',
//        //'MODEL_NAME' => 'MODEL.ELEMENT.NAME',
//        //'CITY_NAME' => 'CITY.ELEMENT.NAME'
//    ])
//    ->registerRuntimeField(
//        null,
//        new \Bitrix\Main\Entity\ReferenceField('MODEL',
//            \Models\Lists\CarManufacturerPropertyValuesTable::getEntity(),
//        ['=this.MODEL' => 'ref.IBLOCK_ELEMENT_ID']
//        )
//    )
//    ->fetchAll();
//
//dump($cars);



// добавление данных  записей в инфоблок Автомобили
//$dbResult = CarsTable::add([
//        'NAME'=>'BMW X5',
//        'MODEL_ID'=>33,
//        'CITY_ID'=>29,
//        'CREATE'=>date('d.m.Y H:i:s'),
//]);
//dump($dbResult);



// удаление записи из БД
// $res = \Bitrix\Iblock\Elements\ElementcarTable::delete(137);
// pr($res);


// редактирование записей в БД
/*\Bitrix\Main\Loader::IncludeModule("iblock");
// делаем запрос на тзменение поля NAME в записи с ID 138
$res = \Bitrix\Iblock\Elements\ElementcarTable::update(138, array(
    'NAME' => 'TEST 777',
));
pr($res);*/

/*$cars = \Bitrix\Iblock\Elements\ElementcarTable::query()
    ->addSelect('NAME')
    ->addSelect('MODEL') // имя свойства
    ->addSelect('ID')
    ->setFilter(array('=ID' => 138))
->fetchCollection();

foreach ($cars as $car) {
        $car->setModel('X5 TEST'); // изменение значения свойства MODEL
        $car->save(); // сохранение данных
}*/



