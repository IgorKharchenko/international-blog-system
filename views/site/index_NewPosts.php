<?php
use yii\helpers\Html;
use app\models\Blog;
use app\models\Post;
use yii\data\Pagination;
use yii\widgets\LinkPager;
?>

<div class="index_BlogsList">
    <?php
        $posts = Post::findLatestPosts(5); // Get 5 latest posts
    ?>
    <!-- Latest Posts List -->
    <?php foreach($posts as $post): ?>
        <h4><?= 'ஐ  '. Html::a(Html::encode($post->title), ['post/view', 'id' => $post->id, 'blog_id' => Blog::getBlogByAuthorId($post->author_id)->id]) ?></h4>
        <h5><?= Html::a(Html::encode($post->findAuthorUsername($post->id)->username), ['user/view', 'id' => $author->id]) ?></h5>
        <br/>
    <?php endforeach; ?>
    <!-- Latest Posts List -->
    <?= Html::a('All Blogs &raquo;', ['blog/index'], ['class' => 'btn btn-default']) ?>
</div>
