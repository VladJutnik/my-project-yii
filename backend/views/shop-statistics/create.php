<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopStatistics */

$this->title = 'Create Shop Statistics';
$this->params['breadcrumbs'][] = ['label' => 'Shop Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-statistics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
