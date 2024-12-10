<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use \Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class tau_feedbackrating extends CModule
{
    public $MODULE_ID = 'tau.feedbackrating';
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;

    public function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        }

        $this->MODULE_NAME = "+++Получение отзыва о качестве работы";
        $this->MODULE_DESCRIPTION = $this->MODULE_NAME;
    }

    public function DoInstall()
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallFiles();
        $APPLICATION->IncludeAdminFile("Установка модуля " . $this->MODULE_NAME, __DIR__ . '/step.php');
        return true;
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля " . $this->MODULE_NAME, __DIR__ . '/unstep.php');
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/admin', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin', true, true);
        CopyDirFiles(__DIR__ . '/components', $_SERVER["DOCUMENT_ROOT"] . '/local/components', true, true);
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/admin', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
    }

    public function InstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            \Tau\Feedbackrating\RatingTable::getEntity()->createDbTable();
        }
    }

    public function UnInstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            // Application::getConnection(\Tau\Feedbackrating\RatingTable::getConnectionName())->queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance("\Tau\Feedbackrating\RatingTable")->getDBTableName());
            // d(Base::getInstance("\Tau\Feedbackrating\RatingTable")->getDBTableName());
            // d(\Tau\FeedbackRating\RatingTable::getTableName());
            // die;
            $connection = \Bitrix\Main\Application::getConnection();
            // $tableName = Base::getInstance("\Tau\Feedbackrating\RatingTable")->getDBTableName();
            $tableName = \Tau\FeedbackRating\RatingTable::getTableName();
            // d($tableName);
            // die;
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
            // d(\Tau\Feedbackrating\RatingTable::getEntity());
            // d(Base::getInstance("\Tau\Feedbackrating\RatingTable")->getDBTableName());

            // d(Base::getInstance("\Tau\Feedbackrating\RatingTable")->getDBTableName());

            // die;
            // // d(\Tau\Feedbackrating\RatingTable::getTableName());
            // die;
            // // $tableName = \Tau\Feedbackrating\RatingTable::getTableName();


        }
    }
}
