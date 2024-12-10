<?php
if (!check_bitrix_sessid()) return;
echo CAdminMessage::ShowNote("Модуль успешно установлен");
?>
<p>Компонент можно добавить в публичной части сайта:</p>
<pre>
$APPLICATION->IncludeComponent(
    "tau:feedbackrating",
    "",
    [
        "DEAL_ID" => 123, // тестовое значение
    ]
);
</pre>

<form action="<?php echo $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?php echo LANGUAGE_ID; ?>">
    <input type="submit" name="" value="<?php echo GetMessage("MOD_BACK"); ?>">
</form>