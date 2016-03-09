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
            [
                'label' => 'Email',
                'value' => ($model->show_email) ? $model->email : 'User had hided own email.',
            ],
            [
                'label' => 'Registration Date',
                'value' => $formatter->asDatetime($model->created_at),
            ],
            [
                'label' => 'Last Login',
                'value' => ($model->last_login) ? $formatter->asDatetime($model->last_login) : $formatter->asDatetime(time()),
            ],
        ],
    ]) ?>
    </div>
