<?php

namespace Events;

use Models\Lists\ZayavkiPropertyValuesTable as ZayavkiTable;
use Otus\Diagnostic\Helper;

class IblockHandler
{
    public static function OnBeforeIBlockUpdateHandler(&$arFields)
    {
        Helper::writeToLog($arFields, 'OnBeforeIBlockUpdateHandler');

    }

    public static function OnAfterIBlockElementUpdate(&$arFields)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('crm');

        Helper::writeToLog($arFields, 'OnAfterIBlockElementUpdate');
        $iblock_id = $arFields['IBLOCK_ID'];

        if ($iblock_id == 25) // Заявки
        {
            $one = ZayavkiTable::getList(['select' => ['*', 'UF_*'], 'filter' => ['IBLOCK_ELEMENT_ID' => $arFields['ID']]])->fetch();
            $deal = \Bitrix\Crm\DealTable::getList(['select' => ['*', 'UF_*'], 'filter' => ["ID" => $one['DEAL']]])->fetch();
            $update = [];
            $price = explode('|', $one['PRICE'])[0];
            $price = number_format($price, 2, '.', '');
            if ($price != $deal['OPPORTUNITY']) {
                $update['OPPORTUNITY'] = $price;
            }
            if ($one['OTVETSTVENNYY'] != $deal['ASSIGNED_BY_ID']) {
                $update['ASSIGNED_BY_ID'] = $one['OTVETSTVENNYY'];
            }
            if (!empty($update)) {
                \Bitrix\Crm\DealTable::update($one['DEAL'], $update);
                Helper::writeToLog([
                    'update' => $update,
                    'one' => $one,
                    'deal' => $deal
                ], 'DealTable update deal = ' . $one['DEAL']);
            }
        }
    }

}