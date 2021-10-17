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
class Loading extends Model
{
    public $name;
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['file'], 'file', 'checkExtensionByMimeType' => false, 'extensions' => 'csv'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Магазин',
            'file' => 'Загрузка csv',
        ];
    }
    function randomFileName($path, $extension)
    {
        do {
            $name = mt_rand(0, 9999999);
            $file = $path . $name . '.'. $extension;
        } while (file_exists($file));
        $name_new = $name . '.'. $extension;
        return $name_new;
    }
}
