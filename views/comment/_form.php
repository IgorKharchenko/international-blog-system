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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <!-- I use iMacros for spawning big amount of info,
        and it doesn't write any info in the CKEditor widget;
         that's why I use this bicycle -->
    <?php $tumbler = false; ?>

    <?php if($tumbler): ?>
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>
    <?php endif; ?>

    <?php if(!$tumbler): ?>
        <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
