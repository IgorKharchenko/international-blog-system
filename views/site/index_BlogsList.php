<?php
use yii\helpers\Html;
use app\models\Blog;
use yii\data\Pagination;
use yii\widgets\LinkPager;
?>

<div class="index_BlogsList">
    <?php
        $query = Blog::find();
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $blogs = Blog::findAllDisplayedBlogs($pagination);
    ?>
    <!-- Blog List -->
    <?php foreach($blogs as $blog): ?>
        <h4><?= '℘  '. Html::a(Html::encode($blog->name), ['post/index', 'blog_id' => $blog->id]) ?></h4>
        <?php $author = Blog::findAuthorOfBlog($blog->id); ?>
        <h5><?= Html::a(Html::encode($author->username), ['user/view', 'id' => $author->id]) ?></h5>
        <br/>
    <?php endforeach; ?>
    <!-- Blog List -->
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    <?= Html::a('All Blogs &raquo;', ['blog/index'], ['class' => 'btn btn-default']) ?>
</div>
