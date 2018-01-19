<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="post-index">
    <h3 align="center">All The Blogs</h3>
    <br/>
    <hr/>
    <?php foreach ($blogs as $blog): ?>
        <h2><?= Html::a(Html::encode("{$blog->name}"), [
                'post/index',
                'blog_id' => $blog->id,
            ]) ?></h2>
        <h4><?= Html::encode("{$blog->title}") ?></h4>
        <br/>
        <?php $formatter = Yii::$app->formatter; ?>
        <h5>
            <?= Icon::show('user') . Html::a(Html::encode($authors_model->searchUsernameById($authors_info, $blog->author_id)), [
                'user/view',
                'id' => $blog->author_id,
            ]) ?>
            | <?= Icon::show('pencil') . $blog->posts_count ?>
        </h5>
        <hr/>

    <?php endforeach; ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
