<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopStatistics */

$this->title = 'Редактировать данные за: ' . $model->data;
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
                    <?php
                    $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'data')->textInput(
                        ['type' => 'date', 'maxlength' => true, 'class' => 'form-control']
                    ) ?>

                    <?= $form->field($model, 'type_case')->dropDownList(Yii::$app->myComponent->statusBookkeeper()) ?>

                    <?= $form->field($model, 'case')->textInput(['class' => 'form-control']) ?>

                    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control']
                    ) ?>

                    <div class="form-group">
                        <?= Html::submitButton(
                            'Сохранить',
                            ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']
                        ) ?>
                    </div>

                    <?php
                    ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
