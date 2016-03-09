<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>
<div class="site-admin_PostsTab">
    <br/>
    <h4> >>> Posts Information <<< </h4>
    <table>
        <tr>
            <td>
                <?= Html::a('Create Post', ['post/create'], ['class' => 'btn btn-warning', 'style' => 'margin:5px']) ?>
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