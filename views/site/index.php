<?php

use yii\helpers\Html;
use app\models\Blog;
use yii\widgets\Pjax;
use yii\bootstrap\Carousel;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1 align="center">International Blog System</h1>

        <p class="lead">The place where you've got your own life.</p>

        <p>
            <?php
            if(Yii::$app->user->isGuest)
                echo Html::a('Let\'s get started with us!', ['site/signup'], ['class' => 'btn btn-primary']);
            else if(Blog::getBlogByAuthorId(Yii::$app->user->id) == NULL)
                echo Html::a('Let\'s create your first blog!', ['blog/create'], ['class' => 'btn btn-success']);
            else
                echo Html::a('Let\'s go in your blog!', ['post/index', 'blog_id' => Blog::getBlogByAuthorId(Yii::$app->user->id)->id], ['class' => 'btn btn-success']);
            ?>
        </p>
    </div>

    <div class="body-content">
        <div class="index_row">
            <div class="index_block">
                <h2>Latest Blogs</h2>
                <br/>
                <p><?= $this->render('index_BlogsList'); ?></p>
            </div>
            <div class="index_block">
                <h2>Latest Posts</h2>
                <br/>
                <p><?= $this->render('index_NewPosts'); ?></p>
            </div>
        </div>

    </div>
</div>