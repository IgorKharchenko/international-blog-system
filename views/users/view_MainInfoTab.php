<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
?>
<div class="view-MainInfoTab">
    <?php $formatter = Yii::$app->formatter; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'label' => 'Registration Date',
                'value' => $formatter->asDatetime($model->created_at),
            ],
            'email:email',
            [
                'label' => 'Last Login',
                'value' => $formatter->asDatetime($model->last_login),
            ],
        ],
    ]) ?>
    </div>
