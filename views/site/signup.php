<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus'   => true,
                'placeholder' => 'Your nickname. This nickname will be used during login on our site.',
            ]) ?>

            <?= $form->field($model, 'email')->textInput([
                'placeholder' => 'Your e-mail address.',
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Your password.',
            ]) ?>

            <hr/>

            <?php $user = new User; ?>

            <?= $form->field($model, 'timezone')
                     ->dropDownList($timeZonesList, [
                             'prompt'   => '(GMT +03:00) Moscow',
                             'timezone' => 'Europe/Moscow',
                         ], [
                             'options' => [
                                 'Europe/Moscow' => ['selected' => true],
                             ],
                         ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Signup', [
                    'class' => 'btn btn-primary',
                    'name'  => 'signup-button',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
