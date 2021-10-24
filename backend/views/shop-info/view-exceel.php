<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

?>

<?php
$form = ActiveForm::begin(); ?>
    <div class="row">
        <?
        $num = 1;
        foreach ($shop_lists as $shop_list):?>
            <div class="col-3">
                <?= $form->field($model, 'shop_' . $num)
                    ->checkbox([
                        'label' => $shop_list->name,
                        'labelOptions' => [
                            'style' => 'padding-left:20px;'
                        ],
                    ]); ?>
            </div>
            <div class="col-9"></div>
            <?
            $num++;
        endforeach;
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(
            'Выгрузить список в Exceel',
            ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']
        ) ?>
    </div>
<?php
ActiveForm::end(); ?>