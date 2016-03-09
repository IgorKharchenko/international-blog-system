<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>

<div class="site-admin">
    <?php
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Users',
                    'content' => $this->render('admin_UsersTab', ['assign_permitted' => $assign_permitted]),
                ],
                [
                    'label' => 'Posts',
                    'content' => $this->render('admin_PostsTab'),
                ],
                [
                    'label' => 'Settings',
                    'content' => $this->render('admin_SettingsTab'),
                ],
            ],
        ]);
    ?>
</div>