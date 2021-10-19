<?php

namespace backend\controllers;

use common\models\Category;
use common\models\LoginForm;
use common\models\ShopInfo;
use common\models\ShopInfoSim;
use common\models\ShopStatistics;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'subjectslist',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'admins',
                            'user-index',
                            'login-input',
                            'view-user',
                            'simulation',
                            'simulation-start'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string|Response
     */
    public function actionIndex()
    {
        $model = new ShopInfo();
        $session = Yii::$app->session;
        $session['user'] = '';
        $session['name'] = '';
        $session['report_category_id'] = '';
        $session['report_data'] = '';
        $shop_null = array('' => 'Выберите ...');
        $shop = ShopInfo::find()->where(['user_id' => Yii::$app->user->identity->id, 'status_view' => '0'])->all();
        $shop_items = ArrayHelper::map($shop, 'id', 'name');
        $shop_items = ArrayHelper::merge($shop_null, $shop_items);

        $category = Category::find()->where(['status_view' => '0'])->all();
        $category_items = ArrayHelper::map($category, 'id', 'name');
        $category_items = ArrayHelper::merge($shop_null, $category_items);
        //надо считать на начало дня за предыдущие дни наверное ??? что бы изменения отобразить
        $category_outlay = []; //категории которые мы расходуем в магазине
        $enrollment = []; //приход на счет
        $outlay = []; //расход со счета
        $sum_enrollment = []; //сумма прихода
        $sum_outlay = []; //сумма расхода
        $data = []; //сумма расхода
        $result_balance = []; //баланс на день
        $result_category = []; //категории для диаграммы
        $category_top = [];//топ трат по категориям
        $balance = 0;
        if ($this->request->isPost) {
            $post = $this->request->post()['ShopInfo'];
            $session['name'] = $post['name'];
            $session['report_category_id'] = $post['report_category_id'];
            $session['report_data'] = $post['report_data'];
            //'enrollment' => 'приход',
            //'outlay' => 'расход'
            $where_sum_enrollment = [
                'shop_id' => $post['name'],
                'type_case' => 'enrollment'
            ];
            $where_sum_outlay = [
                'shop_id' => $post['name'],
                'type_case' => 'outlay'
            ];
            $andwhere = [];

            if ($post['report_category_id']) {
                $where_sum_enrollment += ['category_id' => $post['report_category_id']];
                $where_sum_outlay += ['category_id' => $post['report_category_id']];
                $str_join = ' and shop_statistics.category_id = ' . $post['report_category_id'];
            } else {
                $str_join = '';
            }
            if ($post['report_data']) {
                $andwhere = ['<=', 'data', $post['report_data']];
                $str_join2 = ' and shop_statistics.data <= \'' . Yii::$app->myComponent->dateStrBack(
                        $post['report_data']
                    ) . '\'';
            } else {
                $str_join2 = '';
            }
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
            $sum_enrollment = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
                $where_sum_enrollment
            )->andwhere($andwhere)->asArray()->one();//приход
            $sum_outlay = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
                $where_sum_outlay
            )->andwhere($andwhere)->asArray()->one();//расход
            $statistics = ShopInfo::find()->
            select([
                'shop_info.name as name',
                'shop_statistics.`case` as cost',
                'shop_statistics.`type_case` as type',
                'shop_statistics.`data` as data',
                'shop_statistics.`category_id` as category',
            ])->
            leftJoin('shop_statistics', 'shop_info.id = shop_statistics.shop_id ' . $str_join . ' ' . $str_join2)->
            where(['shop_info.user_id' => Yii::$app->user->identity->id])
                ->andWhere(['shop_id' => $post['name']])
                ->orderBy(['shop_statistics.data' => SORT_ASC])
                ->asArray()
                ->all();
            //создать отлельно массив дат и перебирать их для баланса графика на каждую дату( !?
            foreach ($statistics as $statistic):
                $data[$statistic['data']] = $statistic['data'];
                if ($statistic['type'] == 'outlay') {
                    $outlay[$statistic['data']] += $statistic['cost'];
                    $category_outlay[$statistic['category']] += 1;
                    $category_top[] = [
                        $statistic['name'],
                        $statistic['cost'],
                        $statistic['category'],
                        $statistic['data']
                    ];
                } else {
                    $enrollment[$statistic['data']] += $statistic['cost'];
                }
            endforeach;
            //Подготовка массивов для графиков!
            // собрал массив дат для графика показывать какие даты были в табьлице
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
            foreach ($category_outlay as $key => $category_one):
                //формирую массив для круговой диаграмме по примеру:
                /*'data' => [
                    ['name' => "Safari", 'y' => 8.5],
                    ['name' => "Opera", 'y' => 6.2],
                    ['name' => "Others", 'y' => 0.7],
                ]*/
                $result_category[] = ['name' => Yii::$app->myComponent->categoryName($key), 'y' => $category_one];
            endforeach;
        } else {
            $model->loadDefaultValues();
        }
        if (Yii::$app->user->can('admin')) {
             return $this->redirect('admin/site/admins');
        } else {
            return $this->render('index', [
                'model' => $model,
                'shop_items' => $shop_items,
                'category_items' => $category_items,
                'sum_enrollment' => $sum_enrollment,
                'sum_outlay' => $sum_outlay,
                'category_outlay' => $category_outlay,
                'enrollment' => $enrollment,
                'outlay' => $outlay,
                'balance' => $balance,
                'result_balance' => $result_balance,
                'category_top' => $category_top,
                'result_category' => $result_category,
            ]);
        }
    }

    public function actionAdmins()
    {
        $session = Yii::$app->session;
        $session['user'] = '';
        $session['name'] = '';
        $session['report_category_id'] = '';
        $session['report_data'] = '';
        $model = new ShopInfo();
        $category_null = array('' => 'Выберите ...');
        $user = User::find()->where(['status' => 10])->all();
        $user_items = ArrayHelper::map($user, 'id', 'username');
        $category = Category::find()->where(['status_view' => '0'])->all();
        $category_items = ArrayHelper::map($category, 'id', 'name');
        $category_items = ArrayHelper::merge($category_null, $category_items);
        //надо считать на начало дня за предыдущие дни наверное ??? что бы изменения отобразить
        $shop_items = []; //пустая выборка
        $category_outlay = []; //категории которые мы расходуем в магазине
        $enrollment = []; //приход на счет
        $outlay = []; //расход со счета
        $sum_enrollment = []; //сумма прихода
        $sum_outlay = []; //сумма расхода
        $data = []; //сумма расхода
        $result_balance = []; //баланс на день
        $result_category = []; //категории для диаграммы
        $category_top = [];//топ трат по категориям
        $balance = 0;
        if ($this->request->isPost) {
            $post = $this->request->post()['ShopInfo'];
            $session['user'] = $post['user_id'];
            $session['name'] = $post['name'];
            $session['report_category_id'] = $post['report_category_id'];
            $session['report_data'] = $post['report_data'];
            $shop = ShopInfo::find()->where(['user_id' => $post['user_id'], 'status_view' => '0'])->all();
            $shop_items = ArrayHelper::map($shop, 'id', 'name');
            $where_sum_enrollment = [
                'shop_id' => $post['name'],
                'type_case' => 'enrollment'
            ];
            $where_sum_outlay = [
                'shop_id' => $post['name'],
                'type_case' => 'outlay'
            ];
            $andwhere = [];
            if ($post['report_category_id']) {
                $where_sum_enrollment += ['category_id' => $post['report_category_id']];
                $where_sum_outlay += ['category_id' => $post['report_category_id']];
                $str_join = ' and shop_statistics.category_id = ' . $post['report_category_id'];
            } else {
                $str_join = '';
            }
            if ($post['report_data']) {
                $andwhere = ['<=', 'data', $post['report_data']];
                $str_join2 = ' and shop_statistics.data <= \'' . Yii::$app->myComponent->dateStrBack(
                        $post['report_data']
                    ) . '\'';
            } else {
                $str_join2 = '';
            }
            $sum_enrollment = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
                $where_sum_enrollment
            )->andwhere($andwhere)->asArray()->one();//приход
            $sum_outlay = ShopStatistics::find()->select(['shop_id', 'type_case', 'SUM(`case`) as cost',])->where(
                $where_sum_outlay
            )->andwhere($andwhere)->asArray()->one();//расход
            $statistics = ShopInfo::find()->
            select([
                'shop_info.name as name',
                'shop_statistics.`case` as cost',
                'shop_statistics.`type_case` as type',
                'shop_statistics.`data` as data',
                'shop_statistics.`category_id` as category',
            ])->
            leftJoin('shop_statistics', 'shop_info.id = shop_statistics.shop_id ' . $str_join . ' ' . $str_join2)->
            where(['shop_info.user_id' => $post['user_id']])
                ->andWhere(['shop_id' => $post['name']])
                ->orderBy(['shop_statistics.data' => SORT_ASC])
                ->asArray()
                ->all();
            //создать отлельно массив дат и перебирать их для баланса графика на каждую дату( !?
            foreach ($statistics as $statistic):
                $data[$statistic['data']] = $statistic['data'];
                if ($statistic['type'] == 'outlay') {
                    $outlay[$statistic['data']] += $statistic['cost'];
                    $category_outlay[$statistic['category']] += 1;
                    $category_top[] = [
                        $statistic['name'],
                        $statistic['cost'],
                        $statistic['category'],
                        $statistic['data']
                    ];
                } else {
                    $enrollment[$statistic['data']] += $statistic['cost'];
                }
            endforeach;
            //Подготовка массивов для графиков!
            // собрал массив дат для графика показывать какие даты были в табьлице
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
            foreach ($category_outlay as $key => $category_one):
                //формирую массив для круговой диаграмме по примеру:
                /*'data' => [
                    ['name' => "Safari", 'y' => 8.5],
                    ['name' => "Opera", 'y' => 6.2],
                    ['name' => "Others", 'y' => 0.7],
                ]*/
                $result_category[] = ['name' => Yii::$app->myComponent->categoryName($key), 'y' => $category_one];
            endforeach;
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('admins', [
            'model' => $model,
            'user_items' => $user_items,
            'category_items' => $category_items,
            'sum_enrollment' => $sum_enrollment,
            'sum_outlay' => $sum_outlay,
            'category_outlay' => $category_outlay,
            'enrollment' => $enrollment,
            'outlay' => $outlay,
            'balance' => $balance,
            'result_balance' => $result_balance,
            'category_top' => $category_top,
            'result_category' => $result_category,
            'shop_items' => $shop_items,
        ]);
    }

    public function actionSubjectslist($id)
    {
        $shops = ShopInfo::find()->where(['user_id' => $id, 'status_view' => '0'])->all();
        echo '<option value=" ">Выберите магазин...</option>';
        if (!empty($shops)) {
            foreach ($shops as $key => $shop) {
                echo '<option value="' . $shop->id . '">' . $shop->name . '</option>';
            }
        }
    }

    public function actionUserIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->user->can('admin')) {
            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->where(['status' => 10]),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                        //'title' => SORT_ASC,
                    ]
                ],
            ]);

            return $this->render('user-index', [
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->goHome();
        }
    }

    //для входа под пользователем
    public function actionLoginInput($id)
    {
        $model = User::findOne($id);

        Yii::$app->user->login($model);

        return $this->redirect(['site/index']);
    }

    //для входа под пользователем
    public function actionViewUser($id)
    {
        if (Yii::$app->user->can('admin')) {
            $dataProvider = new ActiveDataProvider([
                'query' => ShopInfo::find()->where(['user_id' => $id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('view-user', [
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->goHome();
        }
    }

    //симуляционная нагрузка пока без отката :(
    public function actionSimulation()
    {
        $str = '';

        return $this->render('simulation', [
            'str' => $str
        ]);
    }

    public function actionSimulationStart()
    {
        $str = '';

        $upl_ids = []; // для отката записей в случаи чего
        for ($i = 0; $i <= 5; $i++) {
            $model_user = new User();
            $model_user->username = $i . 'admin@gmail.com';
            $model_user->auth_key = 'Gc2f3lEMoPzdAE1xvMbmFa_yQudgmR0Z'; //пароль 123456789
            $model_user->password_hash = '$2y$13$.EvBy.HzC/kOvnaT/1BXXuZTrEt/kX1FwYiev.evAZw1hHxdtDuuO';
            $model_user->email = $i . 'admin@gmail.com';
            $model_user->status = '10';
            $model_user->created_at = '1634329569';
            $model_user->updated_at = '1634329569';
            $model_user->verification_token = '00X1lMw1qf23hbVmI0izreeYqbDULp98_1634329569';
            $model_user->save(); //сохран

            $r = new DbManager();
            $r->init();
            $assign = $r->createRole('user');
            $r->assign($assign, $model_user->id);

            $upl_ids[] .= $model_user->id;
        }
        $str .= 'Создано пользователей - ' . $i . ';<br>';

        $amount = [
            '6200',
            '8200',
            '9200',
            '12000',
            '11000',
            '14000',
            '18200',
            '19200',
            '29200',
            '39200',
            '49200',
            '59200',
            '69200',
            '79200',
            '89200',
            '99200',
            '112000',
            '111000',
            '114000',
        ];
        $amount2 = [
            '1',
            '2',
            '3',
        ];
        $item = [//+
            '0' => 'enrollment',
            '1' => 'outlay'
        ];

        $num = 1;
        for ($l = 0; $l < count($upl_ids); $l++) {
            for ($k = 0; $k <= 1; $k++) {
                $model_shop = new ShopInfoSim();
                $model_shop->user_id = $upl_ids[$l];
                $model_shop->name = 'магазин ' . $k;
                $model_shop->description = '';
                $model_shop->status_view = 0;
                $model_shop->save(false); //сохран
                for ($g = 0; $g <= 4; $g++) {
                    $item_rand = mt_rand(
                        0,
                        count($item) - 1
                    ); // Берём случайное число от 0 до (длины массива минус 1) включительно
                    $balance_rand = mt_rand(0, count($amount) - 1);
                    $rand = mt_rand(0, count($amount2) - 1);
                    $model_shopS = new ShopStatistics();
                    $model_shopS->shop_id = $model_shop->id;
                    $model_shopS->category_id = $amount2[$rand];
                    $model_shopS->data = '2021-10-18';
                    $model_shopS->type_case = $item[$item_rand];
                    $model_shopS->case = $amount[$balance_rand];
                    $model_shopS->description = '';
                    $model_shopS->save(false); //сохран
                }
            }
            $num++;
        }

        return $this->render('simulation', [
            'str' => $str
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('admin')) {
                return $this->redirect('site/admins');
            } else {
                return $this->goBack();
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
