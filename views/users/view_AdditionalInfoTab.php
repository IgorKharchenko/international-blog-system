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
            'full_name',
            'sex',
            'country_id',
            'city',
            'about',
        ],
    ]) ?>
    </div>
