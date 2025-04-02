<?php

use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Models\SmartPhoneTable;

Loc::loadMessages(__FILE__);

class otus_homework extends CModule
{
    public $MODULE_ID = 'otus.homework';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('OTUS_VACATION_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('OTUS_VACATION_MODULE_DESC');

        $this->PARTNER_NAME = Loc::getMessage('OTUS_VACATION_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('OTUS_VACATION_PARTNER_URI');
    }


    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);
        Option::set($this->MODULE_ID, 'VERSION_DB', $this->versionToInt());
        $this->InstallDB();
        $this->installFiles();
        $this->InstallEvents();
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installFiles()
    {
        $component_path = $this->GetPath() . '/install/components';

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($component_path)) {
            CopyDirFiles($component_path, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components', true, true);
        } else {
            throw new \Bitrix\Main\IO\InvalidPathException($component_path);
        }
    }

    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $entities = $this->getEntities();

        foreach ($entities as $entity) {
            if (!Application::getConnection($entity::getConnectionName())->isTableExists($entity::getTableName())) {
                Base::getInstance($entity)->createDbTable();
            }
        }

        return true;
    }

    private function getEntities()
    {
        return [
            '\\' . SmartPhoneTable::class
        ];
    }

    public function UninstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = Application::getConnection();

        $entities = $this->getEntities();

        foreach ($entities as $entity) {
            if (Application::getConnection($entity::getConnectionName())->isTableExists($entity::getTableName())) {
                $connection->dropTable($entity::getTableName());
            }
        }

        return true;
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Homework\\Crm\\Handlers',
            'updateTabs'
        );

        return true;
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Vacation\\Crm\\Handlers',
            'updateTabs'
        );

        return true;
    }

    private function versionToInt()
    {
        return intval(preg_replace('/[^0-9]+/i', '', $this->MODULE_VERSION_DATE));
    }

}
