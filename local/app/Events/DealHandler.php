<?php

namespace Events;

use Otus\Diagnostic\Helper;
use Models\Lists\ZayavkiPropertyValuesTable as ZayavkiTable;

class DealHandler
{
    public static function CrmDealUpdate(&$arFields)
    {
        Helper::writeToLog($arFields, 'CrmDealUpdate');
        \Bitrix\Main\Loader::includeModule('iblock');

        if (empty($arFields['ID'])) {
            // Если не передан ID, то это создание сделки
            return true;
        }

        $deal_id = $arFields['ID'];
        $one = ZayavkiTable::getList(['select' => ['*', 'UF_*'], 'filter' => ['DEAL' => $deal_id]])->fetch();
        $update = [];
        if (!empty($arFields['OPPORTUNITY'])) {
            $price_in = $arFields['OPPORTUNITY'];
            $price = explode('|', $one['PRICE'])[0];
            $price = number_format($price, 2, '0', '');
            if($price != $price_in){
                $update['PRICE'] = $price_in.'|RUB';
            }
        }

        if (!empty($arFields['ASSIGNED_BY_ID'])) {
            $user_oo = $arFields['ASSIGNED_BY_ID'];
            if($one['OTVETSTVENNYY'] != $user_oo){
                $update['OTVETSTVENNYY'] = $user_oo;
            }
        }
        if (!empty($update)) {
            ZayavkiTable::update($one['IBLOCK_ELEMENT_ID'], $update);
        }

        return true;
    }
}