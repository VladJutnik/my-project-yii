<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ShopStatisticsSearch extends ShopStatistics
{

    public function rules()
    {
        return [

            [['category_id', 'data', 'type_case'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params,$id)
    {
        $query = ShopStatistics::find()->where(['shop_id' => $id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'forcePageParam' => false,
                //'pageSizeParam' => false,
                'pageSize' => 25
            ]
        ]);
        if($this->data == ''){
            $query->andFilterWhere(['=', 'category_id', $this->category_id])
                ->andFilterWhere(['=', 'type_case', $this->type_case]);
        }else{
            $query->andFilterWhere(['=', 'category_id', $this->category_id])
                ->andFilterWhere(['=', 'data', Yii::$app->myComponent->dateStrBack($this->data)])
                ->andFilterWhere(['=', 'type_case', $this->type_case]);
        }



       /* $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['=', 'organization_id', $this->organization_id]);*/

        return $dataProvider;
    }
}