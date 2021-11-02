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
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
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

    public function beforeSave($insert)
    {
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

    /**
     * Gets query for [[User]].
     *
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     */
    public function getShopInfoAll($str_join, $str_join2, $name, $user_id)
    {
        //Тестим запросы в БД и пишем код запросов в yii
        //SELECT shop_id, type_case, SUM(`case`) as sum FROM `shop_statistics` WHERE shop_id = 1 and type_case = 'outlay'
        //SELECT shop_id, type_case, SUM(`case`) as sum FROM `shop_statistics` WHERE shop_id = 1 and type_case = 'enrollment' and `data` <= '2021-10-16'
        //SELECT
        //    shop.name,
        //    statistics.`case` AS `cost`,
        //    statistics.`type_case` AS `type`,
        //    statistics.data AS `data`,
        //    statistics.`category_id` AS `category`
        //FROM `shop_info`as shop
        //LEFT JOIN `shop_statistics` as statistics ON (shop.id = statistics.`shop_id` and statistics.data <= '2021-10-17')
        //WHERE shop.user_id = 1
        //'enrollment' => 'приход',
        //'outlay' => 'расход'
        /*$sum_enrollment = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(['shop_id' => $post['name'], 'type_case' => 'enrollment', $where_category_sum])->asArray()->one();//приход
        $sum_outlay = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(['shop_id' => $post['name'], 'type_case' => 'outlay', $where_category_sum])->asArray()->one();//расход
        */

        $statistics = ShopInfo::find()->
        select([
            'shop_info.name as name',
            'shop_statistics.`case` as cost',
            'shop_statistics.`type_case` as type',
            'shop_statistics.`data` as data',
            'shop_statistics.`category_id` as category',
        ])->
        leftJoin('shop_statistics', 'shop_info.id = shop_statistics.shop_id ' . $str_join . ' ' . $str_join2)->
        where(['shop_info.user_id' => $user_id])
            ->andWhere(['shop_id' => $name])
            ->orderBy(['shop_statistics.data' => SORT_ASC])
            ->asArray()
            ->all();

        return $statistics;
    }

    /**
     * Gets query for [[User]].
     *
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     */
    public function getShopEnrollmen($where_sum_enrollment, $andwhere)
    {
        $sum_enrollment = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
            $where_sum_enrollment
        )->andwhere($andwhere)->asArray()->one();//приход

        return $sum_enrollment;
    }

    /**
     * Gets query for [[User]].
     *
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     */
    public function getShopOutlay($where_sum_outlay, $andwhere)
    {
        $sum_outlay = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
            $where_sum_outlay
        )->andwhere($andwhere)->asArray()->one();//расход

        return $sum_outlay;
    }
}
