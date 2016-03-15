<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <h4>Name of the category must contain only english symbols.</h4>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <h4>Title of the category can contain any information about the category.</h4>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create Category' : 'Update Category', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
