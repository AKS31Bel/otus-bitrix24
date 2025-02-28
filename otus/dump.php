<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle("Отладка Битрикса");

use Bitrix\Main\Diag\Debug;
Debug::startTimeLabel("testLabel");

function quickSort(array $array): array
{
    if (count($array) <= 1) {
        return $array;
    }

    $left = $right = [];
    reset($array);
    $pivot = array_shift($array);

    foreach ($array as $item) {
        if ($item <= $pivot) {
            $left[] = $item;
        } else {
            $right[] = $item;
        }
    }

    return array_merge(quickSort($left), [$pivot], quickSort($right));
}


$info = [
    "SERVER" => $_SERVER,
    "GET" => $_GET,
    "POST" => $_POST,
    "COOKIE" => $_COOKIE,
    "SESSION" => $_SESSION,
];

//Debug::dump($info, "Info");
//Debug::writeToFile($info, "Info");

$test = [
    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,
    -1,-2,-3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13,-14,-15,-16,-17,-18,-19,-20,-21,-22,-23,-24,
];

Debug::dump($test, "test");
$result = quickSort($test);
Debug::dump($result, "result");

Debug::endTimeLabel("testLabel");
Debug::dump(Debug::getTimeLabels());

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';