<?php

namespace Rest;

use Bitrix\Main\Event;
use Bitrix\Rest\RestException;
use Models\SmartphoneTable as Phones;
use Bitrix\Main\Localization\Loc;
use Otus\Diagnostic\Helper;
Loc::loadMessages(__FILE__);

class SmartPhone
{
    /**
     * Register the REST API
     * @return array[]
     */
    public static function OnRestServiceBuildDescriptionHandler()
    {
        Loc::getMessage('REST_SCOPE_OTUS.SMARTPHONE');

        return [
            'otus.smartphone' => [
                'otus.smartphone.add' => [__CLASS__, 'add'],
                'otus.smartphone.delete' => [__CLASS__, 'delete'],
                'otus.smartphone.get' => [__CLASS__, 'get'],
                'otus.smartphone.list' => [__CLASS__, 'list'],
                'otus.smartphone.update' => [__CLASS__, 'update'],

                \CRestUtil::EVENTS => [
                    'onAfterSPhoneAdd' => ['main', 'onAfterOtusSmartPhoneAdd', [__CLASS__, 'prepareEventData']],
                    'onAfterSPhoneDelete' => ['main', 'onAfterOtusSmartPhoneDelete', [__CLASS__, 'prepareEventData']],
                    'onAfterSPhoneUpdate' => ['main', 'onAfterOtusSmartPhoneUpdate', [__CLASS__, 'prepareEventData']],
                ],
            ]
        ];
    }

    /**
     * Add new phone
     * @param $arParams - request params
     * @param $navStart - navigation
     * @param CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function add ($arParams, $navStart, \CRestServer $server)
    {
        $arParams = array_change_key_case($arParams, CASE_LOWER);
        Helper::writeToLog($arParams, "otus.smartphone.add <arParams>");
        Helper::writeToLog($navStart, "otus.smartphone.add <navStart>");
        Helper::writeToLog($server, "otus.smartphone.add <server>");

        $status = Phones::add($arParams);
        if ($status->isSuccess()) {
            $id = $status->getId();
            $arParams['id'] = $id;
            $event = new Event('main', 'onAfterOtusSmartPhoneAdd', $arParams);
            $event->send();
            return $id;
        }
        else{
            throw new RestException(
                json_encode($status->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }
    }

    /**
     * Get all phones
     * @param $arParams
     * @param $navStart
     * @param CRestServer $server
     * @return array|null
     */
    public static function list ($arParams, $navStart, \CRestServer $server)
    {
        Helper::writeToLog($arParams, "otus.smartphone.list <arParams>");
        Helper::writeToLog($navStart, "otus.smartphone.list <navStart>");
//        Helper::writeToLog($server, "otus.smartphone.list <server>");
        $limit = 5;
        $offset = 0;
        if($navStart >= 1)
            $offset = $limit * ($navStart-1);
        try {
            return Phones::getList([
                'select' => [
                    'id',
                    'name',
                    'price',
                    'screen_id',
                    'battery_id',
                    'SCREEN_NAME'=>'SCREEN.ELEMENT.NAME',
                    'BATTERY_NAME'=>'BATTERY.ELEMENT.NAME',
                ],
                'order' => ['id' => 'desc'],
                'offset' => $offset,
                'limit' => $limit,
            ])->fetchAll();
        }
        catch (Exception $e) {
            throw new RestException(
                json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }

    }

    /**
     * Update phone
     * @param $arParams
     * @param $navStart
     * @param CRestServer $server
     * @return string
     * @throws RestException
     */

    public static function update ($arParams, $navStart, \CRestServer $server)
    {
        $arParams = array_change_key_case($arParams, CASE_LOWER);
        if(!isset($arParams['id'])){
            throw new RestException(
                json_encode(Loc::getMessage('REST_SCOPE_OTUS.NOTID'), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        if(count($arParams) == 1){
            throw new RestException(
                json_encode(Loc::getMessage('REST_SCOPE_OTUS.ONEFIELD'), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        $id = $arParams['id'];
        unset($arParams['id']);
        $status = Phones::update($id, $arParams);
        if ($status->isSuccess()) {
            $arParams['id'] = $id;
            $event = new Event('main', 'onAfterOtusSmartPhoneUpdate', $arParams);
            $event->send();
            return "Update success ID: ".$arParams['id'];
        }
        else{
            throw new RestException(
                json_encode($status->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }
    }

    /**
     * Delete phone
     * @param $arParams
     * @param $navStart
     * @param CRestServer $server
     * @return string
     * @throws RestException
     */

    public static function delete ($arParams, $navStart, \CRestServer $server)
    {
        $arParams = array_change_key_case($arParams, CASE_LOWER);
        if(!isset($arParams['id'])){
            throw new RestException(
                json_encode(Loc::getMessage('REST_SCOPE_OTUS.NOTID'), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        $status = Phones::delete($arParams['id']);
        if ($status->isSuccess()) {
            return "Delete success ID: ".$arParams['id'];
        }
        else{
            throw new RestException(
                json_encode($status->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }
    }

    /**
     * Get phone by id
     * @param $arParams
     * @param $navStart
     * @param CRestServer $server
     * @return array|false|null
     * @throws RestException
     */

    public static function get ($arParams, $navStart, \CRestServer $server)
    {
        Helper::writeToLog($arParams, "otus.smartphone.get <arParams>");
        $arParams = array_change_key_case($arParams, CASE_LOWER);
        if(!isset($arParams['id'])){
            throw new RestException(
                json_encode(Loc::getMessage('REST_SCOPE_OTUS.NOTID'), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }

        try {
            return Phones::getList([
                'select' => [
                    'id',
                    'name',
                    'price',
                    'screen_id',
                    'battery_id',
                    'SCREEN_NAME'=>'SCREEN.ELEMENT.NAME',
                    'BATTERY_NAME'=>'BATTERY.ELEMENT.NAME',
                ],
                'filter' => ['id' => $arParams['id']],
                'limit' => 1,
            ])->fetch();
        }
        catch (Exception $e) {
            throw new RestException(
                json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
    }

    /**
     * Prepare event data
     * @param $arguments - request params
     * @param $handler - handler
     * @return mixed
     */
    public static function prepareEventData($arguments, $handler)
    {
        Helper::writeToLog($arguments, "prepareEventData <arguments>");
        Helper::writeToLog($handler, "prepareEventData <handler>");

        $event = reset($arguments);
        $response = $event->getParameters();
        Helper::writeToLog($response, "prepareEventData <response>");
        return $response;
    }

}