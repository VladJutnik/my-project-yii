<?php
namespace common\components;

use common\models\User;
use yii\base\Component;

class MyComponent extends Component
{
    public function userName($id)
    {
        $model = User::findOne($id);
        return $model->username;
    }

    public function twoColumnName()
    {
        return ['options' => ['class' => 'row mt-2 mb-0 ml-0 mr-0'], 'labelOptions' => ['class' => 'col-sm-12 col-md-12 col-lg-6 col-xl-6 col-form-label font-weight-bold']];
    }

    public function twoColumnInput()
    {
        return ['class' => 'form-control col-sm-12 col-md-12 col-lg-6 col-xl-6'];
    }
}