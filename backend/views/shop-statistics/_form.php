<?php

use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<div class="shop-statistics-form">

    <?php
    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')
        ->widget(
            Select2::classname(),
            [
                'data' => $shop_items,
                'options' => [
                    'placeholder' => 'Выберите или введите...',
                    'options' => [$id => ['Selected' => true]],
                ],
                'pluginOptions' => ['allowClear' => true]
            ]
        ) ?>

    <?
    $url = \yii\helpers\Url::to(['list']); ?>
    <?=
    $form->field($model, 'category_id')->widget(Select2::classname(), [
        'options' => ['value' => $value, 'placeholder' => 'поиск ...'],
        'pluginOptions' => [
            'theme' => true,
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Ожидание результатов...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ])->label('Категория');
    ?>
    <?= $form->field($model, 'data')->textInput(['type' => 'date', 'maxlength' => true, 'class' => 'form-control']) ?>

    <?= $form->field($model, 'type_case')->dropDownList(Yii::$app->myComponent->statusBookkeeper()) ?>

    <?= $form->field($model, 'case')->textInput(['class' => 'form-control']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
    </div>

    <?php
    ActiveForm::end(); ?>

</div>
