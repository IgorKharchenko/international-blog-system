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

?>
<div class="comment-view">
    <h2>
        Comments (<?= $model->comments_count ?>)
    </h2>
    <?php foreach($comments as $comment): ?>

        <?php $formatter = Yii::$app->formatter;
              $tmp_comment = new Comment;
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

</div>