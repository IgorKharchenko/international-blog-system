<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $blogId int */

$this->title = 'Update Category: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => 'Categories',
    'url'   => [
        'index',
        'blog_id' => $blogId,
    ],
];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
