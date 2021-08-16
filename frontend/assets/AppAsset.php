<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    const APIKEY = 'e666f398-c983-4bde-8f14-e3fec900592a';

    public $css = [
        'css/site.css',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css',
        'css/normalize.css',
        'css/style.css'
    ];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?apikey=' . self::APIKEY . '&lang=ru_RU',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js',
        'js/main.js',
        'js/lightbulb.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
