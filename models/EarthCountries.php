<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $code
 */
class EarthCountries extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%earth_countries}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Country Name [RU]',
            'name_en' => 'Country Name [EN]',
            'code' => 'Country Code',
        ];
    }

    /**
     * Gets all the countries in one DepDrop Widget
     */
    public static function getCountriesList()
    {
        $query = new Query;
        return EarthCountries::find()
            ->select('id, name_en')
            ->orderBy('name_en')
            ->all();
    }

    /**
     * Gets a country english name by her id
     * @param $id
     * @return mixed $query Country information
     */
    public static function getCountryNameById($id)
    {
        if(is_int($id))
            return $country_name_en = EarthCountries::findOne($id)->name_en;
        else
            return '';
    }
}