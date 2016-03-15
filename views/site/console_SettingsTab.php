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
    <table>
        <tr>
            <td>
                <?= Alert::widget([
                    'options' => ['class' => 'alert-success'],
                    'body' => 'Nothing to display in this time.
                               We are still working at this page!',
                ]); ?>
            </td>
        </tr>
    </table>
</div>