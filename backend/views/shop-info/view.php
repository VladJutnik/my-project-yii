<?php

use yii\grid\GridView;
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
    <div class="container-fluid">
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
                <hr/>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => [
                        'class' => 'menus-table table-responsive'],
                    'tableOptions' => [
                        'class' => 'table table-bordered table-responsive'
                    ],
                    'rowOptions' => ['class' => 'grid_table_tr'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class' => 'grid_table_th'],
                        ],
                        [
                            'header' => 'Управление',
                            'class' => 'yii\grid\ActionColumn',
                            'options' => ['style' => 'width: 165px; max-width: 165px;'],
                            'template' => '{view} {update} {delete}</div>',
                            'contentOptions' => ['class' => 'action-column text-center', 'style' => 'width: 165px; max-width: 165px;'],
                            'buttons' => [

                                'view' => function ($url, $model, $key) {
                                    return Html::a('<span class="lni lni-magnifier"></span>', ['shop-statistics/view?id=' . $model->id], [
                                        'title' => Yii::t('yii', 'Посмотреть'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-outline-primary'
                                    ]);
                                },
                                'update' => function ($url, $model, $key) {
                                    return Html::a('<span class="lni lni-pencil-alt"></span>', ['shop-statistics/update?id=' . $model->id], [
                                        'title' => Yii::t('yii', 'Редактировать'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-outline-primary'
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::a('<span class="lni lni-trash"></span>', ['shop-statistics/delete?id=' . $model->id], [
                                        'title' => Yii::t('yii', 'Удалить'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-outline-danger',
                                        'data' => ['confirm' => 'Вы уверены что хотите удалить?'],
                                    ]);
                                },
                            ],
                        ],
                        [
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->categoryName($model->category_id);
                            },
                            'filter' => $categories,
                            'headerOptions' => ['class' => 'grid_table_th'],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'attribute' => 'data',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->dateStr($model->data);
                            },
                            'filterInputOptions' => ['type' => 'date', 'class' => 'form-control', 'value' => ''],
                        ],
                        [
                            'attribute' => 'type_case',
                            'value' => function ($model) {
                                return Yii::$app->myComponent->statusBookkeeper($model->type_case);
                            },
                            'filter' => Yii::$app->myComponent->statusBookkeeper(),
                        ],
                        'case',
                        'description',
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
