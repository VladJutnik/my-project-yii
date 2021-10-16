<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopStatistics */

$this->title = Yii::$app->myComponent->dateStr($model->data);;

\yii\web\YiiAsset::register($this);
?>
<div class="shop-statistics-view">
    <div class="container">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <p>
                        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы действительно хотите удалить категорию: ' .  $model->data . '?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
                <hr/>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'shop_id',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->shopName($model->shop_id);
                            },
                        ],
                        [
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->categoryName($model->category_id);
                            },
                        ],
                        [
                            'attribute' => 'data',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->dateStr($model->data);
                            },
                        ],
                        [
                            'attribute' => 'type_case',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->statusBookkeeper($model->type_case);
                            },
                        ],
                        'case',
                        'description',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
