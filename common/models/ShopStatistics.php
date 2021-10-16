<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_statistics".
 *
 * @property int $id
 * @property int $shop_id
 * @property int $category_id
 * @property string $data
 * @property string $type_case
 * @property string $description
 *
 * @property Category $category
 * @property ShopInfo $shop
 */
class ShopStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'category_id', 'data', 'type_case', 'description'], 'required'],
            [['shop_id', 'category_id'], 'integer'],
            [['data'], 'string', 'max' => 10],
            [['type_case'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopInfo::className(), 'targetAttribute' => ['shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'category_id' => 'Category ID',
            'data' => 'Data',
            'type_case' => 'Type Case',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Shop]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(ShopInfo::className(), ['id' => 'shop_id']);
    }
}
