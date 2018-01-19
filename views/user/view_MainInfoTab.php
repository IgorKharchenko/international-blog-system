<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;
use app\models\Blog;

/* @var $this yii\web\View */
/* @var $model app\models\User */
?>
<div class="view-MainInfoTab">
    <?= DetailView::widget([
        'model'      => $model,
        //'encodeLabels' => false,
        'attributes' => [
            'username',
            [
                'label' => 'Email',
                'value' => ($model->show_email) ? $model->email : 'User hided own email.',
            ],
        ],
    ]) ?>
</div>
