<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */

$this->title = 'Admin Page';
?>
<div class="site-admin_SettingsTab">
    <br/>
    <h4> >>> User Settings <<< </h4>
    <?php if($status_em == 'ok'): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => 'Email showing '.(($model->show_email == 1) ? "en" : "dis").'abled successfully!',
        ]); ?>
    <?php endif; ?>
    <?php if($status_tz == 'ok'): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => 'User '.$assigned_user->username.' has been assigned to <b>' .$setRole. '</b> role!',
        ]); ?>
    <?php endif; ?>
    <table>
        <tr>
            <td>
                <?php if($model->show_email == 'false'): ?>
                    <?= Html::a('Show email to other users', ['user/show-email', 'mode' => 'show'], ['class' => 'btn-xs btn-warning', 'style' => 'margin:5px']) ?>
                <?php endif; ?>
                <?php if($model->show_email == 'true'): ?>
                    <?= Html::a('Don\'t show email to other users', ['user/show-email', 'mode' => 'hide'], ['class' => 'btn-xs btn-warning', 'style' => 'margin:5px']) ?>
                <?php endif; ?>
            </td>
            <td>

            </td>
        </tr>
    </table>
</div>