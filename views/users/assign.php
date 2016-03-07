<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Role Assign';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($modelIsNull): ?>
        <h3><strong>No registered users yet!</strong> Please register a user for assign admin privilegies.</h3>
    <?php endif; ?>

    <?php if($setRole): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => 'User has been assigned to <b>' .$setRole. '</b> role!',
        ]); ?>
    <?php endif; ?>

    <?php if(!$modelIsNull): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => "<p>In this page you can assign roles to others.</p>
                        <p>This page is accessible to authors <b>only</b>
                        if there aren't any registered admins.</p>"
        ]); ?>

        <br/><br/>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'username',
                ['label' => 'Role', 'value' => function($model) {
                    return $model->getRole($model->id);
                }],
                [
                    'attribute' => 'assignToAdmin',
                    'value' => function($data) {
                        return Html::a('Assign To Admin role',Url::to(['users/assign', 'id' => $data->id, 'role' => 'admin']));
                        },
                    'format' => 'raw',
                ],
                [
                'attribute' => 'assignToAuthor',
                'value' => function($data) {
                    return Html::a('Assign To Author role',Url::to(['users/assign', 'id' => $data->id, 'role' => 'author']));
                },
                'format' => 'raw',
            ]
            ],
        ]); ?>

        <?= LinkPager::widget(['pagination' => $pagination]) ?>

    <?php endif; ?>

</div>
<?php Pjax::end(); ?>
