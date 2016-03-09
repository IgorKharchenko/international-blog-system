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
    <h4> >>> Users Information <<< </h4>
    <?php
        if($assign_permitted):
            echo Html::a('Assign Privilegies To Users', ['user/assign'], ['class' => 'btn btn-success', 'style' => 'margin:5px']);
        endif;
        echo Html::a('List of All Users', ['user/index'], ['class' => 'btn btn-info', 'style' => 'margin:5px']);
    ?>
</div>