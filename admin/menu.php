<?php

IncludeModuleLangFile(__FILE__);

if ($APPLICATION->GetGroupRight("tau_feedbackrating") !== "D") {
    $aMenu = [
        "parent_menu" => "global_menu_marketing",
        "sort" => 1000,
        "text" => GetMessage("FEEDBACKRATING_MENU_TITLE"),
        "title" => GetMessage("FEEDBACKRATING_MENU_TITLE"),
        "url" => "tau_feedbackrating.php",
        "icon" => "default_menu_icon",
        "page_icon" => "default_page_icon",
    ];

    return $aMenu;
}

return false;
