<?php

namespace backend\controllers;

use common\models\Category;
use common\models\ExceelCommon;
use common\models\Loading;
use common\models\ShopInfo;
use common\models\ShopStatistics;
use common\models\ShopStatisticsSearch;
use Mpdf\Mpdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopInfoController implements the CRUD actions for ShopInfo model.
 */
class ShopInfoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'loading',
                            'create-statistic',
                            'print-shop',
                            'view-exceel',
                            'onoff'
                        ],
                        'allow' => true,
                        'roles' => [
                            'admin',
                            'user'
                        ],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash(
                        "error",
                        "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!"
                    );
                    return $this->redirect(Yii::$app->request->referrer);
                }
            ],
        ];
    }

    /**
     * Lists all ShopInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ShopInfo::find()->where(['user_id' => Yii::$app->user->identity->id]),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopInfo model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $madel = $this->findModel($id);
        $categories = ArrayHelper::map(
            Category::find()->where(['status_view' => '0'])->orderBy(['name' => SORT_ASC])->all(),
            'id',
            'name'
        );

        $searchModel = new ShopStatisticsSearch();
        $search = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($search, $id);

        return $this->render('view', [
            'model' => $madel,
            'categories' => $categories,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    //печать
    public function actionPrintShop($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        $where_sum_enrollment = [
            'shop_id' => $id,
            'type_case' => 'enrollment'
        ];
        $where_sum_outlay = [
            'shop_id' => $id,
            'type_case' => 'outlay'
        ];
        $shop = ShopInfo::findOne($id);
        $sum_enrollment = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
            $where_sum_enrollment
        )->asArray()->one();//приход
        $sum_outlay = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
            $where_sum_outlay
        )->asArray()->one();//расход
        $statistics = ShopInfo::find()->
        select([
            'shop_info.name as name',
            'shop_statistics.`case` as cost',
            'shop_statistics.`type_case` as type',
            'shop_statistics.`data` as data',
            'category.`name` as category_name',
        ])->
        leftJoin('shop_statistics', 'shop_info.id = shop_statistics.shop_id')->
        leftJoin('category', 'category.id = shop_statistics.category_id')->
        where(['shop_info.user_id' => Yii::$app->user->identity->id])
            ->andWhere(['shop_id' => $id])
            ->orderBy(['shop_statistics.data' => SORT_ASC])
            ->asArray()
            ->all();
        //SELECT
        //shop . name,
        //statistics . `case` as `cost`,
        //statistics . `type_case` as `type`,
        //statistics . data as `data`,
        //category . `name` as `category`
        //FROM `shop_info`as shop
        //LEFT JOIN `shop_statistics` as statistics ON(shop . id = statistics . `shop_id`)
        //LEFT JOIN `category` as category ON(category . id = statistics . `category_id`)
        //WHERE shop . user_id = 1

        $category_outlay = []; //категории которые мы расходуем в магазине
        $enrollment = []; //приход на счет
        $outlay = []; //расход со счета
        $data = []; //сумма расхода
        $result_balance = []; //баланс на день
        $category_top = [];//топ трат по категориям
        $balance = 0;
        foreach ($statistics as $statistic):
            $data[$statistic['data']] = $statistic['data'];
            if ($statistic['type'] == 'outlay') {
                $outlay[$statistic['data']] += $statistic['cost'];
                $category_outlay[$statistic['category_name']] += 1;
                $category_top[] = [
                    $statistic['name'],
                    $statistic['cost'],
                    $statistic['category_name'],
                    $statistic['data']
                ];
            } else {
                $enrollment[$statistic['data']] += $statistic['cost'];
            }
        endforeach;
        $data = array_values($data);
        //буду искать значения в массивах прихода и расхода, для расчета остатка денег на счету за дату!
        foreach ($data as $data_one):
            //расчитываем баланс на чсисло для графика
            if ($outlay[$data_one]) {
                $balance = $balance - $outlay[$data_one];
            }
            if ($enrollment[$data_one]) {
                $balance = $balance + $enrollment[$data_one];
            }
            //посчитали баланс на дату после сложения и вычитания
            $result_balance[$data_one] = $balance;
        endforeach;
        // <table style="margin-top: -50px; font-size: 12px; margin-right: -60px; width: 300px;">

        $this->layout = false;
        $html = $this->render(
            '/documents-html/shop-info',
            [
                'shop' => $shop,
                'result_balance' => $result_balance,
                'enrollment' => $enrollment,
                'outlay' => $outlay,
            ]
        );

        $mpdf = new Mpdf([
            'margin_top' => 5,
            'margin_left' => 20,
            'margin_right' => 10,
            'mirrorMargins' => true
            //Установлено значение 1, в документе будут отображаться значения левого и правого полей на нечетных и четных страницах, т. е. они станут внутренними и внешними полями.
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Данные по магазину: ' . $shop->name . '.pdf', 'I'); //D - скачает файл!
    }

    //выгрузка в эксель! с выбором
    public function actionViewExceel()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = false;

        $model = new ExceelCommon();
        $shop_lists = ShopInfo::find()->where(['user_id' => Yii::$app->user->identity->id])->limit(10)->all();

        if (Yii::$app->request->post()) {
            print_r(Yii::$app->request->post());
        }


        return $this->render('view-exceel', [
            'model' => $model,
            'shop_lists' => $shop_lists,
        ]);
    }

    /**
     * Creates a new ShopInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopInfo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCreateStatistic($id)
    {
        $this->layout = false;
        $model = new ShopStatistics();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create-statistic', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * Deletes an existing ShopInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //Удаляем все записи о приходе и расходе по организации!
        ShopStatistics::deleteall(['shop_id' => $id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionOnoff($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->get()['status_veiws'];
        if ($status == 1) {
            $model->status_veiws = 1;
        } else {
            $model->status_veiws = $status;
        }
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    //загрузка cvs
    public function actionLoading()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        $model = new Loading();
        $shop_null = array('' => 'Выберите магазин ...');
        $shop = ShopInfo::find()->where(['user_id' => Yii::$app->user->identity->id, 'status_view' => '0'])->all();
        $shop_items = ArrayHelper::map($shop, 'id', 'name');
        $shop_items = ArrayHelper::merge($shop_null, $shop_items);
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['Loading'];
            if ($_FILES) {
                //print_r(dirname(__DIR__). '\web\upl\ ' );
                //print_r($post);
                $path = dirname(__DIR__) . '\web\upl\ '; //папака в которой лежит файл
                $extension = strtolower(
                    substr(strrchr($_FILES['Loading']['name']['file'], '.'), 1)
                );//узнали в каком формате файл пришел
                $file_name = $model->randomFileName(
                    $path,
                    $extension
                ); // сделали новое имя с проверкой есть ли такое имя в папке
                if (move_uploaded_file(
                    $_FILES['Loading']['tmp_name']['file'],
                    $path . $file_name
                )) { // переместили из временной папки, в которую изначально загрулся файл в новую директорию с новым именем
                    if (($file_list = fopen($path . $file_name, 'r')) !== false) {//ищем файл в директории
                        $j = 0;
                        $out = [];
                        //читаем фйал в директории
                        while (($data = fgetcsv($file_list, 50000, ";"))) {
                            for ($i = 0; $i < count($data); $i++) {
                                $out[$j][$i] .= $data[$i];
                            }
                            $j++;
                        }
                        unset($out[0]);//удалил первую строку
                        $out_save = array_values($out);
                        $upl_id = []; // для отката записей в случаи чего
                        for ($i = 0; $i < count($out_save); $i++) {
                            if ($out_save[$i][1] != '') {
                                $model_upl = new ShopStatistics();
                                $model_upl->shop_id = $post['name'];
                                $model_upl->category_id = $out_save[$i][1];
                                $model_upl->data = Yii::$app->myComponent->dateStrBack($out_save[$i][2]);
                                $model_upl->type_case = $out_save[$i][3];
                                $model_upl->case = $out_save[$i][4];
                                $model_upl->description = $out_save[$i][5];

                                if ($model_upl->save()) {
                                    $upl_id[] .= $model_upl->id;
                                } else {
                                    ShopStatistics::deleteall(['in', 'id', $upl_id]
                                    ); //удаляем всех загрузившихся пациентов если ошибка
                                    Yii::$app->session->setFlash("error", "Что то пошло не так");
                                    return $this->render('loading', [
                                        'model' => $model,
                                        'shop_items' => $shop_items,
                                    ]);
                                }
                            }
                        }

                        Yii::$app->session->setFlash("success", "Все отлично");
                        return $this->render('loading', [
                            'model' => $model,
                            'shop_items' => $shop_items,
                        ]);
                    } else {
                        Yii::$app->session->setFlash('error', "Не удалось прочесть файл!");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Не удалось загрузить файл!");
                }
            } else {
                Yii::$app->session->setFlash('error', "Что то пошло не так!");
            }
        }
        return $this->render('loading', [
            'model' => $model,
            'shop_items' => $shop_items,
        ]);
    }

    /**
     * Finds the ShopInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ShopInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
