<?

namespace Tau\FeedbackRating;

use \Bitrix\Main\Entity;

class RatingTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "tau_feedbackrating";
    }

    // подключение к БД, если не указывать, то будет использовано значение по умолчанию подключения из файла .settings.php. Если указать, то можно выбрать подключение, которое может быть описано в .setting.php
    public static function getConnectionName()
    {
        return "default";
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new Entity\IntegerField('DEAL_ID'),
            new Entity\IntegerField('OPERATOR'),
            new Entity\IntegerField('POLITENESS'),
            new Entity\IntegerField('SPEED'),
            new Entity\DatetimeField('CREATED_AT', [
                'data_type' => 'datetime',
                'required' => true,
                'default_value' => new \Bitrix\Main\Type\DateTime(),
                'title' => 'Дата создания',
            ])
        ];
    }
}
