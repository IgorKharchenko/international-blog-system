<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;
use yii\widgets\LinkPager;
use yii\bootstrap\Alert;
use app\models\Post;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = "Our Post Overview";
$this->params['breadcrumbs'][] = "Our Post Overview";
?>
<div class="post-our_posts">
    <?php if(empty($posts)): ?>
        <?= Alert::widget([
            'options' => [
                'class' => 'alert-warning'
            ],
            'body' => '<b>You don\'t have any posts yet!</b> Maybe it\'s a time to create one?'
        ]); ?>
    <?php endif; ?>
    <?php Icon::map($this); ?>

    <?php $formatter = Yii::$app->formatter; ?>

    <?php foreach($posts as $post): ?>
        <h4><?= Html::a("{$post->title}", ['post/view', 'id' => $post->id]) ?></h4>
        <h5><?= Html::encode("{$post->anons}") ?></h5>

        <b><?= Icon::show('user') . $post_author->username ?></b>
        |  <i><?= Icon::show('calendar') . $formatter->asDatetime($post->publish_date) ?></i>
        |  <?= Icon::show('comment') . $post->comments_count ?>
        |  Publish Status: <b><?= Html::encode("{$post->publish_status}") . "ed" ?> | </b>

        <!-- Buttons -->
            <?= Html::a('Update', ['update', 'id' => $post->id], ['class' => 'btn-xs btn-info']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $post->id, 'route' => $post->publish_status], [
                'class' => 'btn-xs btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <!-- End Buttons -->
        <br/> <hr/>
    <?php endforeach; ?>


</div>