<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Info about user ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
?>
<div class="users-view">

    <h1>User Page</h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <!-- Update User Info Button -->
    <?php if(Yii::$app->user->can('updateOwnUser', ['user' => $model]) ): ?>
        <?= Html::a('Update User Info', ['users/update', 'id' => $model->id], ['class' => 'btn-lg btn-info']); ?>
    <?php endif; ?>
    <!-- End Button -->
    <br/><br/>
    <!-- Delete User Button -->
    <?php if(Yii::$app->user->can('deleteOwnUser', ['user' => $model])): ?>
        <?= Html::a('Delete this user', ['users/delete', 'id' => $model->id], [
            'class' => 'btn-xs btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete your own page?',
                'method' => 'post',
            ],
        ]); ?>
    <?php endif; ?>
    <!-- End Button -->

</div>
