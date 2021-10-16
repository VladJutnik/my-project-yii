<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->name;
//\yii\web\YiiAsset::register($this);
?>
<div class="category-view">
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
                                'confirm' => 'Вы действительно хотите удалить категорию: '.$model->name.'?',
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
