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
<?php
$this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php
        $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php
        $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php
    $this->beginBody() ?>

    <div class="wrapper">
        <?
        if (!Yii::$app->user->isGuest) { ?>
            <!--sidebar-wrapper-->
            <div class="sidebar-wrapper" data-simplebar="true">
                <div class="sidebar-header">
                    <div>
                        <h6 class="logo-text">Тестовая программа</h6>
                    </div>
                    <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
                    </a>
                </div>
                <!--боковое меню-->
                <ul class="metismenu" id="menu">
                    <?
                    if (Yii::$app->user->can('admin')) { ?>
                        <li><?= Html::a(
                                '<div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i></div> <div class="menu-title">Главная</div>',
                                ['/site/admins']
                            ) ?></li>
                        <?
                    } else { ?>
                        <li><?= Html::a(
                                '<div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i></div> <div class="menu-title">Главная</div>',
                                ['/site/index']
                            ) ?></li>
                        <?
                    } ?>
                    <li><?= Html::a(
                            '<div class="parent-icon icon-color-4"><i class="lni lni-database"></i></div> <div class="menu-title">Список категорий</div>',
                            ['/category/index']
                        ) ?></li>
                    <li><?= Html::a(
                            '<div class="parent-icon icon-color-3"><i class="lni lni-shopping-basket"></i></div> <div class="menu-title">Список магазинов</div>',
                            ['/shop-info/index']
                        ) ?></li>
                    <li><?= Html::a(
                            '<div class="parent-icon icon-color-6"><i class="lni lni-upload"></i></div> <div class="menu-title">Загрузка cvs</div>',
                            ['/shop-info/loading']
                        ) ?></li>
                    <?
                    if (Yii::$app->user->can('admin')) { ?>
                        <li><?= Html::a(
                                '<div class="parent-icon icon-color-5"><i class="lni lni-users"></i></div> <div class="menu-title">Список пользователей</div>',
                                ['/site/user-index']
                            ) ?></li>
                        <li><?= Html::a(
                                '<div class="parent-icon icon-color-8"><i class="lni lni-vector"></i></div> <div class="menu-title">Симуляция</div>',
                                ['/site/simulation']
                            ) ?></li>
                        <?
                    } ?>
                </ul>
            </div>
            <header style="position: static !important;" class="top-header">
                <nav class="navbar navbar-expand">

                    <div class="right-topbar ml-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown dropdown-user-profile">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                   data-toggle="dropdown">
                                    <div class="media user-box align-items-center">
                                        <div class="media-body user-info">
                                            <p class="user-name mb-0">Пользователь:</p>
                                            <p class="designattion mb-0"><?= Yii::$app->user->identity->username ?></p>
                                        </div>
                                        <!--<img src="/image_user/<?
                                        /*=Yii::$app->user->identity->photo*/ ?>" class="user-img" alt="Ваш автар">-->
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <?= Html::a(
                                        '<i class="bx bx-user"></i><span>Профиль</span>',
                                        ['/user-settings/profile'],
                                        ['class' => 'dropdown-item']
                                    ) ?>
                                    <?= Html::a(
                                        '<i class="bx bx-cog"></i><span>Настройки</span>',
                                        ['/user-settings/settings'],
                                        ['class' => 'dropdown-item']
                                    ) ?>
                                    <div class="dropdown-divider mb-0"></div>
                                    <?= Html::a(
                                        '<i class="bx bx-power-off"></i><span>Выход</span> ',
                                        ['/site/logout'],
                                        [
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                            'class' => 'dropdown-item'
                                        ]
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <?
        } ?>
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

    <?php
    $this->endBody() ?>
    </body>
    </html>
<?php
$this->endPage();
