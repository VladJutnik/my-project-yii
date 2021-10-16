<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shop Statistics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-statistics-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Shop Statistics', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'shop_id',
            'category_id',
            'data',
            'type_case',
            //'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>