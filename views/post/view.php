<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use app\models\User;
use app\models\Post;
use app\models\Comment;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
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


        <?= $this->render('@app/views/comment/viewAllCommentsInPost.php', [
            'model' => $model,
            'post_author' => $post_author,
            'userHasPrivilegies_Post' => $model->checkUDPrivilegies($model), // checks user permissions
            'comments' => $comments,
            'comments_authors_model' => $comments_authors_model,
            'comments_authors_info' => $comments_authors_info,
            'comments_pagination' => $comments_pagination,
        ]) ?>

    </h5>
</div>
    <hr>
<div class="comment-create">
    <?php if(!Yii::$app->user->isGuest): ?>
        <h2>Leave a comment</h2></br>
        <?= $this->render('@app/views/comment/_form.php', [
            'model' => new Comment(),
        ]) ?>
    <?php endif; ?>
</div>
<?php Pjax::end(); ?>
