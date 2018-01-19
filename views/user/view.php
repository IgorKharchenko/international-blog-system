<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Info about user ' . $model->username;
$this->params['breadcrumbs'][] = $model->username;
?>
<?php Pjax::begin(); ?>
<div class="users-view">
    <!-- Blog Created modal window -->
    <?php if(isset($_GET['blog']) && $_GET['blog'] === 'created'): ?>
        <?php Modal::begin([
            'clientOptions' => ['show' => true]
        ]); ?>
            <?= $this->render('view_BlogCreatedModal',['model' => $model]); ?>
        <?php Modal::end(); ?>
    <?php endif; ?>
    <!-- End Blog Created modal window -->

    <!-- Error Modal Window -->
    <?php if(isset($_GET['error'])): ?>
        <?php Modal::begin([
            'clientOptions' => ['show' => true]
        ]); ?>
        <?= $this->render('view_ErrorMessageModal',['error' => $_GET['error']]); ?>
        <?php Modal::end(); ?>
    <?php endif; ?>
    <!-- End Error Modal Window -->

    <!-- Gravatar -->
    <?= \cebe\gravatar\Gravatar::widget([
        'email' => $model->email,
        'options' => [
            'alt' => $model->username,
        ],
        'size' => 150,
    ]) ?>
    <!-- End Gravatar -->
    <h1>User Page</h1>

    <!-- Tabs -->
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
            [
                'label' => 'User Blog',
                'content' => $this->render('view_UserBlogTab', ['model' => $model, 'blog' => $blog]),
            ]
        ],
    ]); ?>
    <!--  End Tabs -->

    <?php if($model->checkUDPrivilegies($model)): ?>
        <hr/>

        <!-- Update User Info Button -->
        <?= Html::a('Update User Info', ['user/update', 'id' => $model->id], ['class' => 'btn-lg btn-info']); ?>
        <!-- End Button -->

        <br/><br/>

        <!-- Delete User Button -->
        <?= Html::a('Delete user', ['user/delete', 'id' => $model->id], [
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
