<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <!-- User not have Rights -->
        <?php if($error == 'no_rights'): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => 'You don\'t have rights to do that!',
        ]); ?>
        <?php endif; ?>

        <!-- User can't create one more blog  -->
        <?php if($error == 'blog_create'): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => 'You don\'t can create one more blog!',
            ]); ?>
        <?php endif; ?>
    </p>
</div>
