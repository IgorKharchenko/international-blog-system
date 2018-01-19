<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Tabs;
use app\models\Blog;

/* @var $this yii\web\View */

$this->title = 'Console';
?>

<div class="site-admin">
<?php if(!$assign_permitted)
        echo $this->render('console_BlogTab', ['blog' => Blog::getBlogByAuthorId(Yii::$app->user->id)]);
     ?>

<?php if($assign_permitted):
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Your Blog',
                    'content' => $this->render('console_BlogTab', ['blog' => Blog::getBlogByAuthorId(Yii::$app->user->id)]),
                ],
                [
                    'label' => 'Users',
                    'content' => $this->render('console_UsersTab', ['assign_permitted' => $assign_permitted]),
                ],
                [
                    'label' => 'Settings',
                    'content' => $this->render('console_SettingsTab'),
                ],
            ],
        ]);
        endif;
    ?>
</div>