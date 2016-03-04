<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;
use yii\widgets\LinkPager;
use yii\bootstrap\Alert;
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
    <?php foreach($posts as $post): ?>
        <h2><?= Html::a("{$post->title}", ['post/view', 'id' => $post->id]) ?></h2>

        <h4><?= Html::encode("{$post->anons}") ?></h4>
        <h6>Publish Status: <b><?= Html::encode("{$post->publish_status}") . "ed" ?></b></h6>

        <!-- Buttons -->
        <?php if((Yii::$app->user->can('updatePost') && Yii::$app->user->can('deletePost'))
            || (Yii::$app->user->can('updateOwnPost', ['post' => $post]) && Yii::$app->user->can('deleteOwnPost', ['post' => $post]) )): ?>

            <?= Html::a('Update', ['update', 'id' => $post->id], ['class' => 'btn-xs btn-info']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $post->id], [
                'class' => 'btn-xs btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <!-- End Buttons -->


        <?php endif; ?>

        </br>
    <?php endforeach; ?>


</div>