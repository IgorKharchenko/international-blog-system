<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
?>
<div class="view-BlogCreatedModal">
    <p><h3>Blog Created Successfully!</h3></p>
    <p><h5>Go <?= Html::a('create some posts', ['site/console']) ?> on your new blog!</h5></p>
</div>
