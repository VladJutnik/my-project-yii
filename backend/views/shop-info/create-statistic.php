<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

?>

<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <?php
            $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'shop_id')->textInput() ?>

            <?= $form->field($model, 'category_id')->textInput() ?>

            <?= $form->field($model, 'data')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type_case')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn mt-3 btn-success form-control btn-block']) ?>
            </div>

            <?php
            ActiveForm::end(); ?>

        </div>
    </div>
</div>