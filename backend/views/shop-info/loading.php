<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopStatistics */

$this->title = 'Загрузка через cvs';
?>
<div class="shop-statistics-update">
    <div class="category-update">
        <div class="container">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h1><?= Html::encode($this->title) ?></h1>
                    </div>
                    <hr/>
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

                    <?= $form->field($model, 'name')
                        ->widget(
                            Select2::classname(),
                            ['data' => $shop_items,
                                'options' => ['placeholder' => 'Не указан',
                                    'allowClear' => true,
                                ],
                                'pluginOptions' => ['allowClear' => true]
                            ])->label('Выберете организацию') ?>

                    <?= $form->field($model, 'file')->fileInput(['accept' => '.csv'])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
