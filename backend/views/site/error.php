<?php

/* @var $this yii\web\View */

/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="container">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <hr/>
            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                Вышеуказанная ошибка произошла, когда веб-сервер обрабатывал ваш запрос.
            </p>
            <p>
                Пожалуйста, свяжитесь с администратором, если вы считаете, что это ошибка сервера. Спасибо.
            </p>
        </div>
    </div>
</div>
