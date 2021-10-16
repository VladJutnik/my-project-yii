<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopStatistics */

$this->title = 'Добавление статистики по магазину';
?>
<div class="shop-statistics-create">
    <div class="container">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <hr/>
                <?= $this->render('_form', [
                    'model' => $model,
                    'shop_items' => $shop_items,
                    'id' => $id,
                ]) ?>
            </div>
        </div>
    </div>
</div>
