<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
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

            (Yii::$app->user->can('updatePost'))
                ? ['class' => 'yii\grid\ActionColumn']
                : ['label' => 'View User Page',
                    'attribute' => 'View',
                    'format' => 'raw',
                    'value' => function($data){
                    return Html::a('View',Url::to(['user/view', 'id' => $data->id]));
            }],
        ],
    ]); ?>

</div>
<?php Pjax::end(); ?>
