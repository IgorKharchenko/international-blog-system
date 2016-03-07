<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Info about user ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
?>
<?php Pjax::begin(); ?>
<div class="users-view">

    <h1>User Page</h1>

    <?php if($model->checkUDPrivilegies($model) == true): ?>
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
    <?php endif; ?>
    <?php if($model->checkUDPrivilegies($model) == false): ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    <?php endif; ?>


    <?php if($model->checkUDPrivilegies($model)): ?>
        <!-- Update User Info Button -->
        <?= Html::a('Update User Info', ['users/update', 'id' => $model->id], ['class' => 'btn-lg btn-info']); ?>
        <!-- End Button -->
    <br/><br/>
        <!-- Delete User Button -->
        <?= Html::a('Delete this user', ['users/delete', 'id' => $model->id], [
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
