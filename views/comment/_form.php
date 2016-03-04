<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Post;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*$model->id = $model->getId();
    $model->post_id = $_GET['id'];
    $model->author_id = Yii::$app->user->id;
    $model->publish_date = time();*/ ?>

    <!-- If isset($_GET['post_id']) - that it's update, otherwise it's a new comment
         If it's an update - then comment, post and author ID's gets from DB,
            otherwise gets from Comment model -->

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
