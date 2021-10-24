<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <?php
            $form = ActiveForm::begin(); ?>
            <div class="row">
                <?
                $num = 1;
                foreach ($shop_lists as $shop_list):?>
                    <?= $form->field($model, 'shop_' . $num)
                        ->checkbox([
                            'label' => '',
                            'labelOptions' => [
                                'style' => 'padding-left:20px;'
                            ],
                        ]); ?>
                    <?= $shop_list->name . '<br><br>'; ?>
                    <?
                    $num++;
                endforeach;
                ?>
            </div>
            <?php
            ActiveForm::end(); ?>
        </div>
    </div>
</div>