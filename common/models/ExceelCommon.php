<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\rbac\DbManager;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ExceelCommon extends Model
{
    public $shop_1;
    public $shop_2;
    public $shop_3;
    public $shop_4;
    public $shop_5;
    public $shop_6;
    public $shop_7;
    public $shop_8;
    public $shop_9;
    public $shop_10;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'shop_1',
                    'shop_2',
                    'shop_3',
                    'shop_4',
                    'shop_5',
                    'shop_6',
                    'shop_7',
                    'shop_8',
                    'shop_9',
                    'shop_10',
                ],
                'safe'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}