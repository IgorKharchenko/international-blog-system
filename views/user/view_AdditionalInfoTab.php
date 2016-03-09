<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
?>
<div class="view-AdditionalInfoTab">
    <?php $formatter = Yii::$app->formatter; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['label' => 'Full Name', 'value' => ($model->full_name) ? $model->full_name : 'Not Set'],
            ['label' => 'Sex', 'value' => ($model->sex) ? $model->sex : 'Not Set'],
            'country_id',
            ['label' => 'City', 'value' => ($model->city) ? $model->city : 'Not Set'],
            ['label' => 'About Yourself', 'value' => ($model->about) ? $model->about : 'Not Set'],
        ],
    ]) ?>
    </div>
