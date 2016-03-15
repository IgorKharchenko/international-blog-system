<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
?>
<div class="view-MainInfoTab">
    <?php if(($blog) && ($blog->is_private == 0)): ?>
        <h4><b>Name of the Blog: </b><?= Html::encode($blog->name) ?></h4>
        <h4><b>Blog Title: </b><?= Html::encode($blog->title) ?></h4>
        <br/>
        <?= Html::a('View Blog', ['post/index', 'blog_id' => $blog->id], ['class' => 'btn-lg btn-info']); ?>
        <br/><br/>
    <?php endif; ?>
    <?php if((!$blog) || ($blog->is_private == 1)): ?>
        <h4>This user <b>don't have</b> our blog <b>at this time</b> or user blog is <b>private</b>.</h4>
    <?php endif; ?>
</div>
