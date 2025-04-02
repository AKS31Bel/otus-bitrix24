<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div class="ui-toolbar">
<form action="" method="get">
    <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
        <div class="ui-ctl-after ui-ctl-icon-angle"></div>
        <select class="ui-ctl-element" name="CURRENCY">
            <?php foreach ($arResult['CurrencyLang'] as $currency):?>
            <option value="<?=$currency['CURRENCY']?>" <?php if($arResult['GET']==$currency['CURRENCY']):?>selected="selected"<?php endif;?>><?=$currency['FULL_NAME']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="ui-btn-split">
        <button type="submit" class="ui-btn-split ui-btn-success"><?=Loc::getMessage('T_CURRENT_SUBMIT');?></button>
    </div>
</form>
</div>

<table class="currency-list">
    <?php if (!empty($arResult['CURRENCY'])): ?>
        <tr>
            <td colspan="3">
                <?=Loc::getMessage('T_CURRENT_DATE_RATE');?>
                <strong><?=$arResult['CURRENCY']['DATE_RATE']->format("Y-m-d"); ?></strong>
            </td>
        </tr>
        <tr>
            <td><strong><?=$arResult['CURRENCY']['RATE_CNT'];?></strong> <?=$arResult['CURRENCY']['CURRENCY_FROM_FULL_NAME']; ?></td>
            <td>=</td>
            <td><strong><?=$arResult['CURRENCY']['RATE'];?></strong> <?=$arResult['CURRENCY']['CURRENCY_TO_FULL_NAME']; ?></td>
        </tr>
    <?php endif; ?>
</table>
