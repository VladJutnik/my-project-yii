<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'assets-custom/plugins/metismenu/css/metisMenu.min.css',
        'assets-custom/css/pace.min.css',
        //'assets-custom/js/pace.min.js',
        'assets-custom/css/bootstrap.min.css',
        'assets-custom/css/icons.css',
        'assets-custom/css/app.css',
        //'assets-custom/css/dark-sidebar.css',
        //'assets-custom/css/dark-theme.css',
    ];
    public $js = [
        //'assets-custom/js/popper.min.js',
        'assets-custom/js/bootstrap.min.js',
        'assets-custom/js/app.js',
        'assets-custom/plugins/metismenu/js/metisMenu.min.js',
        //'assets-custom/plugins/simplebar/js/simplebar.min.js',
        //'assets-custom/plugins/perfect-scrollbar/js/perfect-scrollbar.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
