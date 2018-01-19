<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;
use app\models\EarthCountries;

/* @var $this yii\web\View */
/* @var $model app\models\User*/
?>
<div class="view-AdditionalInfoTab">
    <?php $formatter = Yii::$app->formatter; ?>
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            [
                'label' => 'Full Name',
                'value' => ($model->full_name) ? $model->full_name : 'User hadn\'t pointed this information.',
            ],
            [
                'label' => 'Sex',
                'value' => $model->sex,
            ],
            [
                'label' => 'Country',
                'value' => ($model->country_id) ? EarthCountries::findOne($model->country_id)->name_en : 'User hadn\'t pointed this information.',
            ],
            [
                'label' => 'City',
                'value' => ($model->city) ? $model->city : 'User hadn\'t pointed this information.',
            ],
            [
                'label'  => 'About Yourself',
                'value'  => ($model->about) ? $model->about : 'User hadn\'t pointed this information.',
                'format' => 'raw',
            ],
        ],
    ]) ?>
</div>
