<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\Alert;
?>
<div class="site-error">

    <h1>Error!</h1>

    <p>
        <!-- User not have Rights -->
        <?php if($error == 'no_rights'): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => 'You can\'t have rights to do that!',
        ]); ?>
        <?php endif; ?>

        <!-- User can't create one more blog  -->
        <?php if($error == 'blog_create'): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => 'You don\'t can create more than one blog!',
            ]); ?>
        <?php endif; ?>
    </p>
</div>
