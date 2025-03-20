<?php

namespace Models\Lists;

use Bitrix\Main\Entity\ReferenceField;
use Models\AbstractIblockPropertyValuesTable;

class SmartPhoneBatteryPropertyValuesTable extends AbstractIblockPropertyValuesTable
{
    const IBLOCK_ID = 22;

    public static function getMap(): array
    {
        $map = [
            'SMARTPHONE' => new ReferenceField(
                'SMARTPHONE',
                \Models\SmartPhoneTable::class,
                ['=this.IBLOCK_ELEMENT_ID' => 'ref.BATTERY_ID']
            )
        ];

        return parent::getMap() + $map;

    }
}