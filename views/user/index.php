<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $formatter = Yii::$app->formatter; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             'username',
             ['label' => 'Country', 'value' => function($data) {
                 return \app\models\EarthCountries::getCountryNameById($data->country_id);
             }],
             ['label' => 'City', 'value' => 'city'],
             ['label' => 'Registration Time', 'attribute' => 'created_at', 'format' => ['datetime', 'php:d.m.Y, H:i:s']],

             ['label' => 'View User Page',
                 'attribute' => 'View',
                 'format' => 'raw',
                 'value' => function($data){
                     return Html::a('View',Url::to(['user/view', 'id' => $data->id]));
                 },
             ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>

</div>
<?php Pjax::end(); ?>
