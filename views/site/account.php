<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Account';
?>
<div class="site-account">
    <div class="jumbotron">
        <h1>Добрый день, <?php echo Yii::$app->user->identity->username ?> </h1>
        <?php
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
            . Html::submitButton(
                'Logout',
                ['class' => 'btn btn-lg btn-success']
            )
            . Html::endForm();
        ?>
    </div>
</div>
