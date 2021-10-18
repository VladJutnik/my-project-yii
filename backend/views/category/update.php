<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'Редактирование категории: ' . $model->name;
?>

<div class="category-update">
    <div class="container">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <hr/>
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

