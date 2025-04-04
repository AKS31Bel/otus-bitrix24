<?php
/**
 * @global $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
\Bitrix\Main\Loader::includeModule('iblock');

if ($request->isPost()) {
    $doctor_id = $request->get("doctor_id");
    $proc_id = $request->get("proc_id");
    $fio = $request->get("fio");
    $date = $request->get("date");

    $date0 = DateTime::createFromFormat('d.m.Y H:i:s', $date);
    $date0->modify('-3 hours');
    $formattedDateString = $date0->format('Y-m-d H:i:s');

    $count = \Models\Lists\BronPropertyValuesTable::getList([
        "select" => ["*"],
        "filter" => ['VREMYA' => $formattedDateString],
        'count_total' => true,
    ])->getCount();
    if ($count == 0) {
        \Models\Lists\BronPropertyValuesTable::add([
            "VREMYA" => $date,
            "PROTSEDURA" => $proc_id,
            "NAME" => $fio,
        ]);
        echo json_encode(["error" => false, "message" =>"/services/lists/23/view/0/"]);
    }else{
        echo json_encode(["error" => true, "message" =>"Дата уже занята"]);
    }

} else {
    echo json_encode(["error" => true, "message" =>"Ошибка запроса"]);
}