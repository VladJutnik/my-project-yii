<?php

use yii\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список пользователей';
?>
<div class="category-index">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h4 class="mb-0 mt-2 ml-3"><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr/>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'username',
                    'email',
                    [
                        'header' => 'Увправление',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{login-input} {view-user}',
                        'contentOptions' => ['class' => 'action-column text-center'],
                        'buttons' => [
                            'login-input' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-user"></span>', $url, [
                                    'title' => Yii::t('yii', 'Залогиниться под пользователем '),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-danger'
                                ]);
                            },
                            'view-user' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-magnifier "></span>', ['site/view-user?id=' . $model->id], [
                                    'title' => Yii::t('yii', 'Посмотреть информацию о пользователе'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-primary'
                                ]);
                            },

                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>