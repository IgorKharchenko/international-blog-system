<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/*
 * TimeOffset model
 *
 * @property string $timezone
 */
class TimeOffset extends ActiveRecord
{
    public static $offset = 3 * 3600; // Default offset is 'Europe/Moscow'

    /**
     * Returns user offset by Greenwich Mean Time in seconds;
     * If user is guest that timezone is Moscow timezone,
     * else offset is user timezone offset
     * TODO: There MUST be a timezone autodetermination by Javascript!
     * @return float
     */
    public static function getUserOffset()
    {
        if(Yii::$app->user->isGuest) {
            $dateTimeZone = new \DateTimeZone('Europe/Moscow');
        } else {
            $user = User::findIdentity(Yii::$app->user->id);
            $dateTimeZone = new \DateTimeZone($user->timezone);
        }
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(time());
        $dateTime->setTimezone($dateTimeZone);
        $offset = $dateTime->getOffset();
        return $offset;
    }

    /**
     * Sets a cookie with timezone value
     */
    public static function setTimezoneCookie($timezone)
    {
        setcookie('timezone', $timezone, time() + 86400*365);
    }
}