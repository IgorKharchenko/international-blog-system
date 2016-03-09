<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\icons\Icon;
use app\models\User;
use app\models\Post;
use app\models\Comment;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
<div class="post-index">
    <br/>
    <?php foreach($posts as $post): ?>

        <?php
              $tmp_user = new Post;
                $hasPrivilegies_Post = $tmp_user->checkUDPrivilegies($post);
              unset($tmp_user);
        ?>

        <h4><?= Html::a(Html::encode("{$post->title}"), ['post/view', 'id' => $post->id]) ?></h4>
        <h7><?= Html::encode("{$post->anons}") ?></h7>
        <br/>
        <?php $formatter = Yii::$app->formatter; ?>
        <h5>
            <?= Icon::show('user') . Html::a(Html::encode($authors_model->searchUsernameById($authors_info, $post->author_id)), ['user/view', 'id' => $post->author_id]) ?>
            |  <?= Icon::show('calendar') . $formatter->asDatetime($post->publish_date) ?>
            |  <?= Icon::show('comment') . $post->comments_count ?>

                <!-- Buttons -->
                <?php if($hasPrivilegies_Post): ?>

                   |  <?= Html::a('Update', ['post/update', 'id' => $post->id], ['class' => 'btn-xs btn-info']) ?>
                    <?= Html::a('Delete', ['post/delete', 'id' => $post->id, 'route' => 'index'], [
                        'class' => 'btn-xs btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]); ?>
                <?php endif; ?>
                <!-- End Buttons -->
            </h5>
        <hr/>

    <?php endforeach; ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
<?php Pjax::end(); ?>
