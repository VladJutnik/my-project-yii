<?php

namespace backend\controllers;

use common\models\Category;
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
                            'onoff'
                        ],
                        'allow' => true,
                        'roles' => [
                            'admin',
                            'user'
                        ],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash(
                                "error",
                                "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!"
                            );
                            return $this->redirect(Yii::$app->request->referrer);
                        }
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

        $shop = ShopInfo::findOne($id);

        $html = '
            <br>
            <table style="margin-top: -50px; font-size: 12px; margin-right: -60px; width: 300px;">
                <tr><td style="width: 380px;"><b>Название:</b> ' . $shop->name . '<br></td></tr>
                <tr><td style="width: 380px;"><b>ИНН:</b> 111222333<br></td></tr>
                <tr><td style="width: 380px;"><b>Юридический адрес:</b> ул. Героев Революции, 245<br></td></tr>
                <tr><td style="width: 380px;"><b>Директор:</b> Иванов Ивано Иванович<br></td></tr>
            </table>';
        $html .= '   
            <table style="border-collapse: collapse; font-size: 12px; margin-top: 15px;">
            <tr>
                <td style=" padding-right: 15px;">Код ОГРН</td>';
        for ($i = 1; $i <= 10; $i++) {
            $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $i . '</td>';
        }
        $html .= '
            </tr>
            </table>';
        $html .= '
            <div style="margin-top: 35px; font-size: 14px; margin-right: -30px;" align="center"><b>Данные по магазину</b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от ' . date(
                "d.m.Y"
            ) . ' г.</i></span></b></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Список трап:</div>';

        $html .= '
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">С заключительным актом ознакомлен:</div>
           
            <br>  
            <table style="margin-top: -0px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i>______________, ______________</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                   
                    <td align="center"  style="width: 70px;" >М.П. </td>
                </tr> 
                <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i><br><br>Председатель организации: <br>______________, ______________</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td align="center"  style="width: 70px;" ><br><br><br><br>М.П.</td>
                </tr>
            </table>
            </div>
        ';

        require_once __DIR__ . '/../../vendor/autoload.php';

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Данные по магазину: ' . $shop->name . '.pdf', 'D'); //D - скачает файл!
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
