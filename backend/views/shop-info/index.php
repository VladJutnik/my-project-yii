<?php

use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список магазинов';
?>

<div class="shop-info-index">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h4 class="mb-0 mt-2 ml-3"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Добавить новый магазин', ['create'], ['class' => 'btn btn-primary ml-3 px-5 btn-sm']
                    ) ?>
                    <?= Html::a(
                        'Добавить статистику по магазину',
                        ['shop-statistics/create'],
                        ['class' => 'btn btn-primary ml-3 px-5 btn-sm']
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
                    'description',
                    [
                        'header' => 'Увправление',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{onoff} {create-statistic} {view} {print-shop} {update} {delete}',
                        'contentOptions' => ['class' => 'action-column text-center'],
                        'buttons' => [
                            'onoff' => function ($url, $model, $key) {
                                /* if ($model->status_view == 0 || $model->status_view == '')
                                 {
                                     $value = 0;
                                 }
                                 else
                                 {
                                     $value = 1;
                                 }
                                 return SwitchInput::widget(
                                     [
                                         'name' => 'status_view',
                                         'options' => ['data-id' => $model->id],
                                         'type' => SwitchInput::CHECKBOX,
                                         'value' => $value,
                                         'pluginOptions' => [
                                             'size' => 'mini',
                                             'animate' => false,
                                             'onText' => 'Вкл отображение',
                                             'offText' => 'Выкл отображение'
                                         ],
                                         'pluginEvents' => [
                                             "switchChange.bootstrapSwitch" => "
                                                 function (event) {
                                                 var status_view = 0;
                                                 if(jQuery(this).is(':checked')){status_view = 1;}
                                                 var id = jQuery(this).attr('data-id');
                                                 jQuery.ajax({url:'/organizations/onoff?id='+id+'&status_veiws='+status_veiws,
                                                 success:function(model){console.log(model)}})}
                                             ",
                                         ],
                                     ]
                                 );*/
                            },
                            'create-statistic' => function ($url, $model, $key) {
                                /*return
                                    Html::button('<span class="lni lni-plus"></span>', [
                                        'data_id' => $model->id,
                                        'class' => 'btn btn-outline-primary',
                                        'title' => Yii::t('yii', 'Добавить статистические данные'),
                                        'onclick' => '
                                            $.get("create-statistic?id=" + $(this).attr("data_id"), function(data){
                                            $("#showModal .modal-body").empty();
                                            $("#showModal .modal-body").append(data);
                                            $("#showModal").modal("show");
                                        });'
                                    ]);*/
                                return Html::a(
                                    '<span class="lni lni-plus"></span>',
                                    ['shop-statistics/create?id=' . $model->id],
                                    [
                                        //return Html::a('<span class="glyphicon glyphicon-list-alt"></span> Акт', $url, [
                                        'title' => Yii::t('yii', 'Добавить статистические данные'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-outline-primary'
                                    ]
                                );
                            },
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
                            'print-shop' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-printer"></span>', $url, [
                                    'title' => Yii::t('yii', 'Печать информации'),
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
<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить статистические данные:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">

            </div>
        </div>
    </div>
</div>