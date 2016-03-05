<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use app\models\User;
use app\models\Post;
use app\models\Comment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <br/>
    <?php foreach($posts as $post): ?>

        <?php $tmp_user = new Post;
                $user_model = $tmp_user->findAuthorUsername($post->id);
                $hasPrivilegies_Post = $tmp_user->checkUDPrivilegies($post);
              unset($tmp_user);

              $tmp_comment = new Comment;
                $comments_count = $tmp_comment->commentsCount($post->id, $pagination);
              unset($tmp_comment);
        ?>

        <h4><?= Html::a("{$post->title}", ['post/view', 'id' => $post->id]) ?></h4>
        <h7><?= Html::encode("{$post->anons}") ?></h7>
        <br/>
        <?php $formatter = Yii::$app->formatter; ?>
        <h5><i>
                <?= Icon::show('user') . /*$user_model->username*/ $posts->username ?>
                |  <?= Icon::show('calendar') . $formatter->asDatetime($post->publish_date) ?>
                |  <?= Icon::show('comment') . $comments_count ?>
                <!-- Buttons -->
                <?php if($hasPrivilegies_Post): ?>

                    <?= Html::a('Update', ['post/update', 'id' => $post->id], ['class' => 'btn-xs btn-info']) ?>
                <?php endif; ?>
                <!-- End Buttons -->


            </i></h5>
        <hr/>


    <?php endforeach; ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
