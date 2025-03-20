<?php

namespace Models\Lists;

use Bitrix\Main\Entity\ReferenceField;
use Models\AbstractIblockPropertyValuesTable;

class SmartPhoneScreenPropertyValuesTable extends AbstractIblockPropertyValuesTable
{
    const IBLOCK_ID = 21;

    public static function getMap(): array
    {
        $map = [
            'SMARTPHONE' => new ReferenceField(
                'SMARTPHONE',
                \Models\SmartPhoneTable::class,
                ['=this.IBLOCK_ELEMENT_ID' => 'ref.SCREEN_ID']
            )
        ];

        return parent::getMap() + $map;

    }
}