<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Blog;
use app\models\Category;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput([
        'maxlength'   => true,
        'placeholder' => 'Title of the post.',
    ]) ?>

    <?= $form->field($model, 'anons')->textInput([
        'maxlength'   => true,
        'placeholder' => 'Anons of the post.',
    ]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => [
            'rows'    => 8,
            'columns' => 10,
        ],
        'preset'  => 'basic',
    ]) ?>

    <?= $form->field($model, 'categories_selection')
             ->ListBox($model->createArrayForCategoriesSelection(), ['multiple' => true]) ?>

    <?= $form->field($model, 'publish_status')->dropDownList([
        'draft'   => 'Draft',
        'publish' => 'Publish',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
