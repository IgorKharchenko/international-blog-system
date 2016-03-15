<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    /**
     * Sets up MySQL and local server time to GMT +00:00
     */
    public function actionInitGmtTimezone()
    {
        date_default_timezone_set('UTC');
        Yii::$app->db->createCommand('SET GLOBAL `time_zone` = `+00:00`')->execute();
        echo "Setting up time zone to Greenwich is successful.";
        return 0;
    }
}