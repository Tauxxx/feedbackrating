<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = 'tau.feedbackrating';

if ($APPLICATION->GetGroupRight($module_id) == 'D') die('Доступ запрещен');

Loader::includeModule($module_id);

$aTabs = array(
    // TODO:таб для настроек
    // array(
    //     // значение будет вставленно во все элементы вкладки для идентификации (используется для javascript)
    //     "DIV" => "edit1",
    //     "TAB" => "Test tab",
    //     "TITLE" => "Test title",
    //     "OPTIONS" => array(
    //         "Секция 1",
    //         array(
    //             // имя элемента формы, для хранения в бд
    //             "checkbox",
    //             "Текст checkbox",
    //             "N",
    //             array("checkbox"),
    //         ),
    //     )
    // ),
    array(
        "DIV"   => "edit2",
        // название вкладки в табах из основного языкового файла битрикс
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        // заголовок и всплывающее сообщение вкладки из основного языкового файла битрикс
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    )
);

// проверяем текущий POST запрос и сохраняем выбранные пользователем настройки
if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        // цикл по заполненым пользователем данным
        foreach ($aTab["OPTIONS"] as $arOption) {
            // если это название секции, переходим к следующий итерации цикла
            if (!is_array($arOption)) {
                continue;
            }
            // проверяем POST запрос, если инициатором выступила кнопка с name="Update" сохраняем введенные настройки в базу данных
            if ($request["Update"]) {
                // получаем в переменную $optionValue введенные пользователем данные
                $optionValue = $request->getPost($arOption[0]);
            } elseif ($request["default"]) {
                // устанавливаем дефолтные значения параметров и сохраняем в базу данных
                // Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
}

// отрисовываем форму, для этого создаем новый экземпляр класса CAdminTabControl, куда и передаём массив с настройками
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

// отображаем заголовки закладок
$tabControl->Begin();
?>

<form action="<? echo ($APPLICATION->GetCurPage()); ?>?mid=<? echo ($module_id); ?>&lang=<? echo (LANG); ?>" method="post">
    <? foreach ($aTabs as $aTab) {
        if ($aTab["OPTIONS"]) {
            // завершает предыдущую закладку, если она есть, начинает следующую
            $tabControl->BeginNextTab();
            // отрисовываем форму из массива
            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }
    // завершает предыдущую закладку, если она есть, начинает следующую
    $tabControl->BeginNextTab();
    // выводим форму управления правами в настройках текущего модуля
    require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php";
    // подключаем кнопки отправки формы
    $tabControl->Buttons();
    // выводим скрытый input с идентификатором сессии
    echo (bitrix_sessid_post());
    // выводим стандартные кнопки отправки формы
    ?>
    <input class="adm-btn-save" type="submit" name="Update" value="Применить" />
    <input type="submit" name="default" value="По умолчанию" />
</form>
<?
// обозначаем конец отрисовки формы
$tabControl->End();

// // пример получения значения из настроек модуля конкретного поля
// $op = \Bitrix\Main\Config\Option::get(
//     // ID модуля, обязательный параметр
//     "hmarketing.d7",
//     // имя параметра, обязательный параметр
//     "hmarketing_multiselectbox",
//     // возвращается значение по умолчанию, если значение не задано
//     "",
//     // ID сайта, если значение параметра различно для разных сайтов
//     false
// );

// // пример получения значения из настроек модуля всех полей
// $op = \Bitrix\Main\Config\Option::getForModule("hmarketing.d7");

// остальные команды https://dev.1c-bitrix.ru/api_d7/bitrix/main/config/option/index.php
