<?php

use yii\bootstrap4\Html;
$this->title = 'Симуляция - это тест нагрузочный';
echo '<h1>Откат симуляции пока не доделан, но даннная функция создаст вам несколько пользователей, добавит им магазины и статистику :( Удачи</h1>';
echo Html::a('Начать СИМУЛЯЦИЮ!!!!!', ['simulation-start'], ['class' => 'btn btn-warning']).'<br><br><br>';

if (!empty($str)){
    echo $str;
}
?>