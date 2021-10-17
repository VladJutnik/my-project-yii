<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_info".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property int $status_view
 *
 * @property User $user
 */
class ShopInfo extends \yii\db\ActiveRecord
{
    public $report_user;
    public $report_category_id;
    public $report_data;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id', 'status_view', 'report_user'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Кто вносил',
            'name' => 'Название магазина',
            'description' => 'Пометки по магазину',
            'status_view' => 'Статус отображения',
            //поля для отчета
            'report_user' => 'Пользователь',
            'report_category_id' => 'Категория',
            'report_data' => 'Дата',
        ];
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            $this->user_id = Yii::$app->user->identity->id;
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
