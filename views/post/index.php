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

        <?php $author_id = Post::find()
        ->select('author_id')
        ->where('id=:post_id', [':post_id' => $post->id])
        ->one();
        $user_model = User::find()
        ->where('id=:author_id', ['author_id' => $author_id->author_id])
        ->one();
        $comments_count = Comment::find()
            ->where(['post_id' => $post->id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->count();
        ?>

        <h4><?= Html::a("{$post->title}", ['post/view', 'id' => $post->id]) ?></h4>
        <h7><?= Html::encode("{$post->anons}") ?></h7>
        <br/>
        <?php $formatter = Yii::$app->formatter; ?>
        <h5><i>
                <?= Icon::show('user') . $user_model->username ?>
                |  <?= Icon::show('calendar') . $formatter->asDatetime($post->publish_date) ?>
                |  <?= Icon::show('comment') . $comments_count ?>
                <!-- Buttons -->
                <?php if((Yii::$app->user->can('updatePost') && Yii::$app->user->can('deletePost'))
                    || (Yii::$app->user->can('updateOwnPost', ['post' => $post]) && Yii::$app->user->can('deleteOwnPost', ['post' => $post]) )): ?>

                    <?= Html::a('Update', ['post/update', 'id' => $post->id], ['class' => 'btn-xs btn-info']) ?>
                <?php endif; ?>
                <!-- End Buttons -->


            </i></h5>
        <hr/>


    <?php endforeach; ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
