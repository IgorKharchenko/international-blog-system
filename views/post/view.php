<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use app\models\User;
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
            <b><?= Icon::show('user') . $post_author->username ?></b>
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


    </h5>
</div> <hr>

<div class="comment-view">

    <h2>Comments (<?= $model->comments_count ?>)</h2>
    <?php foreach($comments as $comment): ?>

        <?php $tmp_comment = new Comment;
                $userHasPrivilegies_Comment = $tmp_comment->checkUDPrivilegies($comment);
              unset($tmp_comment); ?>
        <hr/>
        <h5>
            <?= Icon::show('user') . Html::a($comments_authors_model->searchUsernameById($comments_authors_info, $comment->author_id), ['users/view', 'id' => $comment->author_id]) ?>
            |  <?= Icon::show('calendar') . $formatter->asDatetime($comment->publish_date) ?>
            <?php if($userHasPrivilegies_Comment): ?>
                |
                <?= Html::a('Update', ['comment/update', 'id' => $comment->id, 'post_id' => $model->id], ['class' => 'btn-xs btn-info']); ?>
                <?= Html::a('Delete', ['comment/delete', 'id' => $comment->id, 'post_id' => $model->id, 'route' => 'index'], [
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
    <?= LinkPager::widget(['pagination' => $comments_pagination]) ?>

</div> <hr>

<div class="comment-create">
    <?php if(!Yii::$app->user->isGuest): ?>
        <h2>Leave a comment</h2></br>
        <?= $this->render('@app/views/comment/_form.php', [
            'model' => new Comment(),
        ]) ?>
    <?php endif; ?>
</div>
<?php Pjax::end(); ?>