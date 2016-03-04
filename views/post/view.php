<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use app\models\User;
use app\models\Comment;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
    <h4><?= Html::encode($this->title) ?></h4>
    <?= $model->content ?>

    <?php $formatter = Yii::$app->formatter; ?>
    </br></br>
    <h5><i>
            <?= Icon::show('user') . $author_model->username ?>
            |  <?= Icon::show('calendar') . $formatter->asDatetime($model->publish_date) ?>
            |         <!-- Buttons -->
            <?php if((Yii::$app->user->can('updatePost') && Yii::$app->user->can('deletePost'))
            || (Yii::$app->user->can('updateOwnPost', ['post' => $model]) && Yii::$app->user->can('deleteOwnPost', ['post' => $model]) )): ?>

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


    </i></h5>
</div> <hr>

<div class="comment-view">
    <h2>Comments (<?= $comments_count ?>)</h2>
    <?php foreach($comments as $comment): ?>

        <?php $author_id = Comment::find()
            ->select('author_id')
            ->where('id=:comment_id', [':comment_id' => $comment->id])
            ->one();
        $user_model = User::find()
            ->where('id=:author_id', ['author_id' => $author_id->author_id])
            ->one();
        ?>


        <h5>
            <hr>
            <?= Icon::show('user') . $user_model->username ?>
            |  <?= Icon::show('calendar') . $formatter->asDatetime($comment->publish_date) ?>
            <?php if((Yii::$app->user->can('updateComment') && Yii::$app->user->can('deleteComment'))
                || (Yii::$app->user->can('updateOwnComment', ['post' => $comment]) && Yii::$app->user->can('deleteOwnComment', ['post' => $comment]) )): ?>

                <?= Html::a('Update', ['comment/update', 'id' => $comment->id, 'post_id' => $model->id,], ['class' => 'btn-xs btn-info']); ?>
                <?= Html::a('Delete', ['comment/delete', 'id' => $comment->id, 'post_id' => $model->id], [
                    'class' => 'btn-xs btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]); ?>

            <?php endif; ?>
            <br/><br/>
            <h4><?= Html::encode("{$comment->title}") ?></h4>

            <?= $comment->content ?>
        </h5>
    <?php endforeach; ?>
    </br>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div> <hr>

<div class="comment-create">
    <?php if(!Yii::$app->user->isGuest): ?>
        <h2>Leave a comment</h2></br>
        <?= $this->render('@app/views/comment/_form.php', [
            'model' => $comment_model,
        ], compact('models')) ?>
    <?php endif; ?>
</div>
