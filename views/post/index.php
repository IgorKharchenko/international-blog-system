<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use yii\bootstrap\Alert;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = substr($blog->name, 0, 200);
?>

<!-- Blog Header -->
<div class="blog-header">
    <h2 align="center"><?= Html::encode($blog->name); ?></h2>
    <br/>
    <h4 align="center"><?= Html::encode($blog->title); ?></h4>
    <br/>
    <h4 align="center">Blog Author: <?= Html::a($author->username, ['user/view', 'id' => $author->id]) ?></h4>
</div>
<!-- End Blog Header -->

<div class="post-index">
    <!-- Error Alert Message -->
    <br/>
    <?php if(($posts == NULL) && $HasPrivilegies_Post): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => '<h4>There are no posts yet. Maybe it\'s a time to '
                . Html::a('create one', ['post/create', 'blog_id' => $blog->id]) . '?</h4>',
        ]) ?>
    <?php endif; ?>
    <?php if(($posts == NULL) && !$HasPrivilegies_Post): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => '<h4>There are no posts yet.</h4>',
        ]) ?>
    <?php endif; ?>
    <hr/>
    <!-- Error Alert Message -->

    <!-- Post Viewing -->
    <?php foreach($posts as $post): ?>

        <h4><?= Html::a(Html::encode("{$post->title}"), ['post/view', 'id' => $post->id, 'blog_id' => $blog->id]) ?></h4>
        <h7><?= Html::encode("{$post->anons}") ?></h7>
        <br/>
        <?php $formatter = Yii::$app->formatter; ?>
        <h5>
            <?= Icon::show('calendar') . $formatter->asDatetime($post->publish_date) ?>
            |  <?= Icon::show('comment') . $post->comments_count ?>
            <?= $this->render('index_CategoriesButtons', ['post' => $post, 'categories_all' => $categories_all, 'blog' => $blog]); ?>

                <?php if($HasPrivilegies_Post): ?>
                    <!-- Buttons -->
                   |  <?= Html::a('Update', ['post/update', 'id' => $post->id, 'blog_id' => $blog->id], ['class' => 'btn-xs btn-info']) ?>
                    <?= Html::a('Delete', ['post/delete', 'id' => $post->id, 'blog_id' => $blog->id, 'route' => 'index'], [
                        'class' => 'btn-xs btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]); ?>
                    <!-- End Buttons -->
                <?php endif; ?>
            </h5>
        <hr/>

    <?php endforeach; ?>
    <!-- Post Viewing -->

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
