<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Info about user ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
?>
<?php Pjax::begin(); ?>
<div class="users-view">


    <?= \cebe\gravatar\Gravatar::widget([
        'email' => $model->email,
        'options' => [
            'alt' => $model->username,
        ],
        'size' => 150,
    ]) ?>
    <h1>User Page</h1>

    <?php
        echo Tabs::widget([
        'items' => [
            [
                'label' => 'Main Info',
                'content' => $this->render('view_MainInfoTab', ['model' => $model]),
            ],
            [
                'label' => 'Additional Info',
                'content' => $this->render('view_AdditionalInfoTab', ['model' => $model]),
            ],
        ],
    ]); ?>


    <?php if($model->checkUDPrivilegies($model)): ?>
        <!-- Update User Info Button -->
        <?= Html::a('Update User Info', ['user/update', 'id' => $model->id], ['class' => 'btn-lg btn-info']); ?>
        <!-- End Button -->
    <br/><br/>
        <!-- Delete User Button -->
        <?= Html::a('Delete this user', ['user/delete', 'id' => $model->id], [
            'class' => 'btn-xs btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete your own page?',
                'method' => 'post',
            ],
        ]); ?>
        <!-- End Button -->
    <?php endif; ?>
</div>
<?php Pjax::end(); ?>
