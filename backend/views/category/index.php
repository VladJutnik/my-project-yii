<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
?>

<div class="category-index">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h4 class="mb-0 mt-2 ml-3"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Добавить новую категорию', ['create'], ['class' => 'btn btn-primary m-1 px-5 btn-sm']
                    ) ?>
                </div>
            </div>
            <hr/>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            return Yii::$app->myComponent->userName($model->user_id);
                        },
                    ],
                    'name',
                    [
                        'header' => 'Увправление',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'contentOptions' => ['class' => 'action-column text-center'],
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-magnifier"></span>', $url, [
                                    'title' => Yii::t('yii', 'Посмотреть'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-primary'
                                ]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-pencil-alt"></span>', $url, [
                                    'title' => Yii::t('yii', 'Редактировать'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-primary'
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-trash"></span>', $url, [
                                    'title' => Yii::t('yii', 'Удалить'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-danger',
                                    'data' => ['confirm' => 'Вы уверены что хотите удалить пользователя?'],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>