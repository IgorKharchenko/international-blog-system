<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>

<div class="site-admin">

    <h3>Blog Console</h3>
    <hr/>
    <?php if($assign_permitted): ?>
        <?= Html::a('Assign privilegies to users', ['users/assign'], ['class' => 'btn btn-success', 'style' => 'margin:5px']) ?>
    <?php endif; ?>
        <?= Html::a('List of All Users Info', ['users/index'], ['class' => 'btn btn-info', 'style' => 'margin:5px']) ?>
    <hr/>
    <h4>In this page you can: </h4>
    <table>
        <tr>
            <td>
                <?= Html::a('Create Post', ['post/create'], ['class' => 'btn btn-warning']) ?>
            </td>
            <td>
                <?= ButtonDropdown::widget([
                    'label' => 'View Our Posts',
                    'options' => [
                        'class' => 'btn btn-warning',
                        'style' => 'margin:5px',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => 'With the Publish status',
                                'url' => ['post/ourposts', 'status' => 'publish'],
                            ],
                            [
                                'label' => 'With the Draft status',
                                'url' => ['post/ourposts', 'status' => 'draft'],
                            ],
                            [
                                'label' => 'All posts',
                                'url' => ['post/ourposts', 'status' => 'all'],
                            ],
                        ]
                    ]
                ]); ?>
            </td>
        </tr>
    </table>
</div>