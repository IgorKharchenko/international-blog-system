<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\db\Query;
use kartik\icons\Icon;
use app\models\Category;
use app\models\Comment;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $blog->name, 'url' => ['post/index', 'blog_id' => $blog->id]];
$this->params['breadcrumbs'][] =  substr($this->title, 0, 200);
?>

<?php Pjax::begin(); ?>
<div class="post-view">
    <h4><?= Html::encode($this->title) ?></h4>
    <?= $model->content ?>

    <?php $formatter = Yii::$app->formatter; ?>
    </br></br>
    <h5>

            <b><?= Icon::show('user') . Html::a(Html::encode($post_author->username), ['user/view', 'id' => $post_author->id]) ?></b>
            |  <i><?= Icon::show('calendar') . $formatter->asDatetime($model->publish_date) ?></i>
            <?= $this->render('index_CategoriesButtons', ['post' => $model, 'categories_all' => $categories_all, 'blog' => $blog]); ?>
            <!-- Buttons -->
            <?php if($userHasPrivilegies_Post): ?>
                |
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn-xs btn-info']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn-xs btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]); ?>
            <?php endif; ?>
            <!-- End Buttons -->
        <hr/>

        <!-- Comments -->
        <?= $this->render('@app/views/comment/viewAllCommentsInPost.php', [
            'model' => $model,
            'post_author' => $post_author,
            'userHasPrivilegies_Post' => $model->checkUDPrivilegies($model, $blog), // checks user permissions
            'comments' => $comments,
            'comments_authors_model' => $comments_authors_model,
            'comments_authors_info' => $comments_authors_info,
            'comments_pagination' => $comments_pagination,
        ]) ?>
        <!-- Comments -->

    </h5>
</div>
    <hr>
<!-- Comments Creating Form -->
<div class="comment-create">
    <?php if(Yii::$app->user->isGuest): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => 'You need to login for comment creating.',
        ]); ?>
    <?php endif; ?>
    <?php if(!Yii::$app->user->isGuest): ?>
        <h2>Leave a comment</h2></br>
        <?= $this->render('@app/views/comment/_form.php', [
            'model' => new Comment(),
        ]) ?>
    <?php endif; ?>
</div>
<!-- End Comments Creating Form -->
<?php Pjax::end(); ?>
