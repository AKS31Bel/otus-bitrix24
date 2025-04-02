<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Models\SmartphoneTable as Phones;

Loc::loadMessages(__FILE__);

class OtusGrid extends CBitrixComponent
{
    const GRID_ID = 'otus_smartphone_grid';
    public function executeComponent(): void
    {
        try {
            $request = Context::getCurrent()->getRequest();

            if (isset($request['order_list'])) {
                $page = explode('page-', $request['order_list']);
                $page = $page[1];
            } else {
                $page = 1;
            }

            $totalRowsCount = Phones::getCount();
            $nav = new \Bitrix\Main\UI\PageNavigation('order_list');
            $nav->allowAllRecords(false)->setPageSize($this->arParams['NUM_PAGE'])->initFromUri();
            $nav->setRecordCount($totalRowsCount);

            // Get grid options
            $gridOptions = new Bitrix\Main\Grid\Options(self::GRID_ID);
            $navParams = $gridOptions->GetNavParams();

            $gridColumns= self::getColumns();
            if (!$gridColumns->isSuccess()) {
                throw new \RuntimeException(implode(', ', $gridColumns->getErrorMessages()));
            }

            $order = ['id' => 'ASC'];
            if(isset($request['by']) && isset($request['order'])) {
                $order = [$request['by'] => $request['order']];
            }

            $limit = $this->arParams['NUM_PAGE']==$navParams['nPageSize']? $this->arParams['NUM_PAGE'] : $navParams['nPageSize'];
            $gridRows = self::getRows($page, $limit, $order);

            if (!$gridRows->isSuccess()) {
                throw new \RuntimeException(implode(', ', $gridRows->getErrorMessages()));
            }

            $this->arResult = [
                'GRID_ID' => self::GRID_ID,
                'COLUMNS' => $gridColumns->getData(),
                'ROWS' => $gridRows->getData(),
                'NAV_OBJECT' => $nav,
                'TOTAL_ROWS_COUNT' => $totalRowsCount,
                'AJAX_MODE' => 'Y',
                'SHOW_ROW_CHECKBOXES' => false,
                'SHOW_ROW_ACTIONS_MENU' => true,
                'SHOW_GRID_SETTINGS_MENU' => true,
                'SHOW_NAVIGATION_PANEL' => true,
                'SHOW_PAGINATION' => true,
                'ALLOW_SORT' => true,
            ];

            $this->IncludeComponentTemplate();

        } catch (\Throwable $e) {
            ShowError('ERROR>> '.$e->getMessage());
        }
    }

    private function getColumns(): Result
    {
        $result = new Result;
        $columns = [
            [
                'id' => 'id',
                'name' => 'id',
                'sort' => 'id',
                'default' => true,
            ],
            [
                'id' => 'name',
                'name' => 'Наименование',
                'sort' => 'name',
                'default' => true,
            ],
            [
                'id' => 'price',
                'name' => 'Стоимость',
                'sort' => 'price',
                'default' => true,
            ],
            [
                'id' => 'SCREEN_NAME',
                'name' => 'Экран',
                'sort' => 'SCREEN_NAME',
                'default' => true,
            ],
            [
                'id' => 'BATTERY_NAME',
                'name' => 'Батарея',
                'sort' => 'BATTERY_NAME',
                'default' => true,
            ],
        ];
        return $result->setData($columns);
    }

    private function getRows(int $page = 1, int $limit, array $order): Result
    {
        $result = new Result;
        $data = [];
        $offset = $limit * ($page-1);

        $phones = Phones::getList([
            'select' => [
                'id',
                'name',
                'price',
                'screen_id',
                'battery_id',
                'SCREEN_NAME'=>'SCREEN.ELEMENT.NAME',
                'BATTERY_NAME'=>'BATTERY.ELEMENT.NAME',
            ],
            'order' => $order,
            'offset' => $offset,
            'limit' => $limit,
        ])->fetchAll();

        //dump($phones);

        foreach ($phones as $phone) {
            $data[] = [
                'id' => $phone['ID'],
                'columns' => $phone
            ];
        }

        return $result->setData($data);
    }
}
