<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
AppAsset::register($this);
/*AppAsset::register($this);*/
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<?php
/*if (Yii::$app->user->isGuest) {
    $this->render('/site/login');
} else {
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}
*/?>

<div class="wrapper">
    <?if (!Yii::$app->user->isGuest) {?>
        <!--sidebar-wrapper-->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div class="">
                    <img src="/images/logo.png" class="logo-icon-2" alt="" />
                </div>
                <div>
                    <h6 class="logo-text">Тестовая программа</h6>
                </div>
                <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
                </a>
            </div>
            <!--боковое меню-->
            <ul class="metismenu" id="menu">
                <li><?= Html::a('<div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i></div> <div class="menu-title">Главная</div>', ['/site/index']) ?></li>
                <li><?= Html::a('<div class="parent-icon icon-color-1"><i class="lni lni-database"></i></div> <div class="menu-title">Список категорий</div>', ['/category/index']) ?></li>
            </ul>
        </div>
        <header style="position: static !important;" class="top-header">
            <nav  class="navbar navbar-expand">

                <div class="right-topbar ml-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown dropdown-user-profile">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-toggle="dropdown">
                                <div class="media user-box align-items-center">
                                    <div class="media-body user-info">
                                        <p class="user-name mb-0">Пользователь:</p>
                                        <p class="designattion mb-0"><?= Yii::$app->user->identity->username ?></p>
                                    </div>
                                    <!--<img src="/image_user/<?/*=Yii::$app->user->identity->photo*/?>" class="user-img" alt="Ваш автар">-->
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?= Html::a('<i class="bx bx-user"></i><span>Профиль</span>', ['/user-settings/profile'], ['class' => 'dropdown-item']) ?>
                                <?= Html::a('<i class="bx bx-cog"></i><span>Настройки</span>', ['/user-settings/settings'], ['class' => 'dropdown-item']) ?>
                                <!--
                                                            --><?/*= Html::a('Ссылка', ['/user-settings/settings'], [
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'id' => Yii::$app->user->identity->id,
                                        'id2' => Yii::$app->user->identity->id,
                                        'param2' => 'value2',
                                    ],
                                ],
                            ]);*/?>

                                <div class="dropdown-divider mb-0"></div>
                                <?= Html::a('<i class="bx bx-power-off"></i><span>Выход</span> ', ['/site/logout'], [
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                    'class' => 'dropdown-item']) ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    <?}?>
    <!--ОСНОВНОЙ КОНТЕНТ-->
    <div class="page-content-wrapper">
        <div style="margin-top: -80px" class="page-content">
            <div class="container mb-3 mt-2">
                <?= Alert::widget() ?>
            </div>
            <?= $content ?>
        </div>
    </div>
    <!--start overlay-->
    <div class="overlay toggle-btn-mobile"></div>
    <!--end overlay-->
    <!--Кнопка на верх--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
