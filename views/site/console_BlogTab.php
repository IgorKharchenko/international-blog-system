<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>
<div class="site-admin_PostsTab">
    <br/>

    <!-- Blog Not Created Alert -->
    <?php if($blog == NULL): ?>
        <h4> >>> User Blog Information <<< </h4>
        <?= Alert::widget([
            'options' => ['class' => 'alert-warning'],
            'body' => '<h4>You don\'t have your blog at this time. Maybe it\'s a time to '
                .Html::a('create one', ['blog/create'], ['class' => 'btn btn-success']) . ' ?</h4>',
        ]); ?>
    <?php endif; ?>
    <!-- Blog Not Created Alert -->

    <!-- Blog Posting Buttons -->
    <?php if($blog != NULL): ?>

        <h1 align="center">Your Blog, Your Rules.</h1>
        <br/>
        <p align="center"><?= Html::a('Go To Your Blog!', ['post/index', 'blog_id' => $blog->id], ['class' => 'btn-lg btn-success', 'style' => 'margin:5px']) ?></p>
        <h3>Your Blog <b>Name</b>: <?= Html::encode($blog->name) ?></h3>
        <h3>Your Blog <b>Title</b>: <?= Html::encode($blog->title) ?></h3>
        <hr/>

        <h4> >>> Make some Posts! <<<</h4>
    <table>
        <tr>
            <td>
                <?= Html::a('Create New Post In Your Blog', ['post/create', 'blog_id' => $blog->id], ['class' => 'btn btn-warning', 'style' => 'margin:5px']) ?>
            </td>
            <td>
                <?= ButtonDropdown::widget([
                    'label' => 'View All Posts In Your Blog',
                    'options' => [
                        'class' => 'btn btn-warning',
                        'style' => 'margin:5px',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => 'With the Publish status',
                                'url' => ['post/ourposts', 'blog_id' => $blog->id, 'status' => 'publish'],
                            ],
                            [
                                'label' => 'With the Draft status',
                                'url' => ['post/ourposts', 'blog_id' => $blog->id, 'status' => 'draft'],
                            ],
                            [
                                'label' => '',
                                'options' => [
                                    'role' => 'presentation',

                                    'style' => 'border: 1px solid orange; background: orange',
                                ]
                            ],
                            [
                                'label' => 'All posts',
                                'url' => ['post/ourposts', 'blog_id' => $blog->id, 'status' => 'all'],
                            ],
                        ]
                    ]
                ]); ?>
            </td>
        </tr>
    </table>
        <hr/>
        <h4> >>> Update Your Blog! <<<</h4>
    <table>
        <tr>
            <td>
                <?= Html::a('Update Info About Your Blog', ['blog/update', 'id' => $blog->id], ['class' => 'btn btn-primary', 'style' => 'margin:5px']) ?>
            </td>
            <td>
                <?= Html::a('Update Categories Of Your Blog', ['category/index', 'blog_id' => $blog->id], ['class' => 'btn btn-primary', 'style' => 'margin:5px']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <hr/>
                <?= Html::a('Delete Your Blog', ['blog/delete', 'id' => $blog->id], [
                    'class' => 'btn-xs btn-danger',
                    'data' => [
                    'confirm' => 'Are you sure you want to delete your own blog?',
                    'method' => 'post',
                ]]); ?>
            </td>
        <tr>
    </table>
    <?php endif; ?>
    <!-- Blog Posting Buttons -->
</div>