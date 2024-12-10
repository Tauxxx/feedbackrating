<?php

use Bitrix\Main\Loader;
// use Bitrix\Main\Application;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

use Tau\Feedbackrating\RatingTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class FeedbackRatingComponent extends CBitrixComponent implements Controllerable
{
    const CRITERIA_MAP = [
        'Взаимодействие' => 'OPERATOR',
        'Вежливость' => 'POLITENESS',
        'Быстрота' => 'SPEED',
    ];

    public function executeComponent()
    {
        if (!Loader::includeModule("tau.feedbackrating")) {
            ShowError("Module not found.");
            return;
        }

        // Логика работы компонента
        $this->arResult = [
            "DEAL_ID" => $this->arParams["DEAL_ID"],
            "CRITERIA_MAP" => self::CRITERIA_MAP,
        ];

        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [
            'rate' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                ],
            ],
        ];
    }

    public function rateAction($DEAL_ID, $criterion, $value)
    {
        if (!Loader::includeModule('tau.feedbackrating')) {
            throw new \Bitrix\Main\SystemException("Module not loaded");
        }

        $field = self::CRITERIA_MAP[$criterion] ?? null;

        if (!$field || !$DEAL_ID || ((int)$value < 1 && (int)$value > 5)) {
            throw new \Bitrix\Main\ArgumentException("Invalid parameters");
        }

        $existingRating = RatingTable::getList([
            'filter' => ['DEAL_ID' => $DEAL_ID],
        ])->fetch();

        if ($existingRating) {
            RatingTable::update($existingRating['ID'], [$field => $value]);
        } else {
            RatingTable::add([
                'DEAL_ID' => $DEAL_ID,
                $field => $value,
            ]);
        }

        return ['status' => 'success'];
    }
}
