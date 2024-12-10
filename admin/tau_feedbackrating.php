<?php

use Bitrix\Main\Loader;
use Tau\Feedbackrating\RatingTable;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// if (!$USER->IsAdmin()) {
//     $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
// }

$module_id = 'tau.feedbackrating';

if (!Loader::includeModule($module_id)) {
    die("Module not found.");
}
if ($APPLICATION->GetGroupRight($module_id) == 'D') die('Доступ запрещен');

$APPLICATION->SetTitle("Оценки обращений");

$sTableID = "ratings_list"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$adminList = new CAdminList($sTableID, $oSort);

// Получение данных из таблицы
$rsData = RatingTable::getList([
    // 'filter' => $filter,
    'order' => [$by => $order],
]);

$rsData = new CAdminResult($rsData, $sTableID);
$adminList->NavText($rsData->GetNavPrint("Оценки"));

// Заголовки таблицы
$adminList->AddHeaders([
    ["id" => "ID", "content" => "ID", "sort" => "ID", "default" => true],
    ["id" => "DEAL_ID", "content" => "Номер обращения", "sort" => "DEAL_ID", "default" => true],
    ["id" => "OPERATOR", "content" => "Оператор", "sort" => "OPERATOR", "default" => true],
    ["id" => "POLITENESS", "content" => "Вежливость", "sort" => "POLITENESS", "default" => true],
    ["id" => "SPEED", "content" => "Скорость", "sort" => "SPEED", "default" => true],
    ["id" => "CREATED_AT", "content" => "Дата", "sort" => "CREATED_AT", "default" => true],
]);

// Заполнение строк таблицы
while ($arRes = $rsData->Fetch()) {
    $row = &$adminList->AddRow($arRes["ID"], $arRes);

    // Настройка отображения колонок
    $row->AddViewField("ID", $arRes["ID"]);
    $row->AddViewField("DEAL_ID", $arRes["DEAL_ID"]);
    $row->AddViewField("OPERATOR", $arRes["OPERATOR"]);
    $row->AddViewField("POLITENESS", $arRes["POLITENESS"]);
    $row->AddViewField("SPEED", $arRes["SPEED"]);
    $row->AddViewField("CREATED_AT", $arRes["CREATED_AT"]);
}

$adminList->AddFooter([
    ["title" => "Всего", "value" => $rsData->SelectedRowsCount()],
]);

$adminList->DisplayList();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
