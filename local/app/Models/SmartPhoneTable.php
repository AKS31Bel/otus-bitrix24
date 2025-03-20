<?php

namespace Models;


use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\FloatField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Validator\Base,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\Entity\Query\Join;

Loc::loadMessages(__FILE__);

use Models\Lists\SmartPhoneScreenPropertyValuesTable as ScreenTable;
use Models\Lists\SmartPhoneBatteryPropertyValuesTable as BatteryTable;

/**
 * Class SmartPhoneTable
 *
 * @package Models
 */
class SmartPhoneTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'smathphone';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            'id' => (new IntegerField('id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'name' => (new StringField('name',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('_ENTITY_NAME_FIELD')),
            'price' => (new FloatField('price',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_PRICE_FIELD')),
            'screen_id' => (new IntegerField('screen_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_SCREEN_ID_FIELD')),
            'battery_id' => (new IntegerField('battery_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_BATTERY_ID_FIELD')),

            (new Reference('SCREEN', ScreenTable::class, Join::on('this.screen_id', 'ref.IBLOCK_ELEMENT_ID')))
                ->configureJoinType('inner'),

            (new Reference('BATTERY', BatteryTable::class, Join::on('this.battery_id', 'ref.IBLOCK_ELEMENT_ID')))
                ->configureJoinType('inner')

        ];
    }

    /**
     * Returns validators for name field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(3, 20),
        ];
    }
}
