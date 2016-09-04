<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$error = Yii::$app->session->getFlash('error');
?>
<?php if($error): ?>
<div class="alert alert-danger">
    <?= nl2br(Html::encode($error)) ?>
</div>
<?php endif;?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Тестовое задание №1</h1>

        <p class="lead">Система аутентификации пользователей</p>

    </div>

</div>
