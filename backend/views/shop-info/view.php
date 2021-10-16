<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shop-info-view">
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
                                'confirm' => 'Вы действительно хотите удалить магазин: '.$model->name.'?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
                <hr/>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        'description:ntext',
                        [
                            'attribute' => 'status_view',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->statusView($model->status_view);
                            },
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
