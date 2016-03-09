<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>
<div class="site-admin_UsersTab">
    <br/>
    <?php
        if($assign_permitted):
            echo Html::a('Assign privilegies to users', ['users/assign'], ['class' => 'btn btn-success', 'style' => 'margin:5px']);
        endif;
        echo Html::a('List of All Users Info', ['users/index'], ['class' => 'btn btn-info', 'style' => 'margin:5px']);
    ?>
</div>