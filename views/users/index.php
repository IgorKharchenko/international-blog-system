<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'encodeLabels' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
             'created_at:datetime',
             'updated_at:datetime',

            (Yii::$app->user->can('updatePost')) ? ['class' => 'yii\grid\ActionColumn'] : ['label' => ':D'],
        ],
    ]); ?>

</div>
