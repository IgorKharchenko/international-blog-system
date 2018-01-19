<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength'   => true,
        'placeholder' => 'Name of blog.',
    ]) ?>

    <?= $form->field($model, 'title')->textInput([
        'maxlength'   => true,
        'placeholder' => 'Title of blog.',
    ]) ?>

    <?= $form->field($model, 'is_private')
             ->checkbox(['checked ' => ($model->is_private == 1) ? true : false]) ?>
    <h5>You can create private blog only for yourself. Nobody can view your posts and write
        comments.</h5>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
