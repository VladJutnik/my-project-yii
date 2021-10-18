<?php

use common\models\Category;
use kartik\select2\Select2;
use miloschuman\highcharts\Highcharts;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use phpnt\chartJS\ChartJs;

$this->title = 'Пример Backend-а админа';
$session = Yii::$app->session;
?>

<div class="site-index">
    <div class="container-fluid">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5>Выбор статистики по магазину:</h5>
                </div>
                <hr/>
                <?php
                $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'user_id')->dropDownList($user_items, [
                    'options' => [$session['user'] => ['Selected' => true]],
                    'class' => 'form-control',
                    'onchange' => '
                        $.get("./subjectslist?id="+$(this).val(), function(data){
                            $("select#shopinfo-name").html(data);
                         });
                        '
                ])->label('Выберите пользователя: '); ?>
                <?= $form->field($model, 'name')
                    ->widget(
                        Select2::classname(),
                        [
                            'data' => $shop_items,
                            'options' => [
                                'placeholder' => 'Выберите или введите...',
                                'options' => [$session['name'] => ['Selected' => true]],
                            ],
                            'pluginOptions' => ['allowClear' => true]
                        ]
                    ) ?>

                <?= $form->field($model, 'report_category_id')
                    ->widget(
                        Select2::classname(),
                        [
                            'data' => $category_items,
                            'options' => [
                                'placeholder' => 'Выберите или введите...',
                                'options' => [$session['report_category_id'] => ['Selected' => true]],
                            ],
                            'pluginOptions' => ['allowClear' => true]
                        ]
                    ) ?>
                <?= $form->field($model, 'report_data')->textInput(
                    [
                        'type' => 'date',
                        'maxlength' => true,
                        'class' => 'form-control',
                        'value' => $session['report_data']
                    ]
                ) ?>
                <div class="form-group">
                    <?= Html::submitButton(
                        'Показать',
                        ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']
                    ) ?>
                </div>

                <?php
                ActiveForm::end(); ?>
                <hr/>
                <?
                if (!empty($category_outlay) || !empty($enrollment) || !empty($outlay) || !empty($sum_enrollment) || !empty($sum_outlay) || !empty($result_balance)) { ?>
                    <h5 class="text-center">Просмотр статистики по магазину:</h5>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="card radius-15 bg-voilet">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="mb-0 text-white"><?= $sum_enrollment['cost'] ?> руб. <i
                                                        class="bx bxs-up-arrow-alt font-14 text-white"></i></h2>
                                        </div>
                                        <div class="ml-auto font-35 text-white"><i class="bx bx-cart-alt"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-white">Доходы</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card radius-15  bg-rose">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="mb-0 text-white"><?= $sum_outlay['cost'] ?> руб. <i
                                                        class="bx bxs-down-arrow-alt font-14 text-white"></i></h2>
                                        </div>
                                        <div class="ml-auto font-35 text-white"><i class="bx bx-cart-alt"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-white">Расходы</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card radius-15 bg-primary-blue">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="mb-0 text-white"><?= $balance ?> руб.</h2>
                                        </div>
                                        <div class="ml-auto font-35 text-white"><i class="bx bx-cart-alt"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-white">Актуальный баланс (на дату или на сегодня)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="m-2">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'backgroundColor' => '#dacef0',
                                    'type' => 'line'
                                ],
                                'title' => ['text' => 'Изменения баланса'],
                                'xAxis' => [
                                    'categories' => array_values(array_flip($result_balance))
                                ],
                                'yAxis' => [
                                    'title' => ['text' => 'Баланс']
                                ],
                                'series' => [
                                    ['name' => 'Баланс', 'data' => array_values($result_balance)],
                                ]
                            ]
                        ]);
                        ?>
                    </div>
                    <hr/>
                    <div class="m-2">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'backgroundColor' => '#dacef0',
                                    'plotBackgroundColor' => null,
                                    'plotBorderWidth' => null,
                                    'plotShadow' => false
                                ],
                                'title' => ['text' => 'Категории трат'],
                                'tooltip' => ['pointFormat' => "{series.name}: <b>{point.percentage:.1f}%</b>"],
                                'accessibility' => ['point' => ['valueSuffix' => '%']],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'color' => '#000000',
                                            'connectorColor' => '#000000',
                                            'format' => "{point.name}"
                                        ],
                                    ]
                                ],
                                'series' => [
                                    [
                                        'type' => 'pie',
                                        'colorByPoint' => true,
                                        'data' => $result_category
                                    ],
                                ]
                            ]
                        ]);
                        ?>
                    </div>
                    <hr/>
                    <table id="tableId" class="table table-bordered table-sm ">
                        <tr>
                            <th colspan="10" class="text-center">Список трат</th>
                        </tr>
                        <tr>
                            <th class="text-center">Магазин</th>
                            <th class="text-center">Сумма</th>
                            <th class="text-center">Категория</th>
                            <th class="text-center">Дата</th>
                        </tr>
                        <?
                        for ($i = 0; $i < 10; $i++) {
                            if ($category_top[$i][0] != '') {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $category_top[$i][0] ?></td>
                                    <td class="text-center"><?= $category_top[$i][1] ?></td>
                                    <td class="text-center"><?= Yii::$app->myComponent->categoryName(
                                            $category_top[$i][2]
                                        ) ?></td>
                                    <td class="text-center"><?= Yii::$app->myComponent->dateStr(
                                            $category_top[$i][3]
                                        ) ?></td>
                                </tr>
                                <?
                            } ?>
                            <?
                        } ?>
                    </table>
                    <?
                } ?>

            </div>
        </div>
    </div>

</div>
