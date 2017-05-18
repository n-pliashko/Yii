<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        "https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.38/browser-polyfill.js",
        "https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.38/browser.js",
        "https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-with-addons.js",
        "https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.js",
        "https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom.js",
        "https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom-server.js",
        '//fb.me/JSXTransformer-0.13.1.js',
//        "https://unpkg.com/react@15/dist/react.min.js",
//        "https://unpkg.com/react-dom@15/dist/react-dom.js",
//        "https://unpkg.com/react-dom@15/dist/react-dom-server.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
    public $jsOptions = ['position' => View::POS_HEAD];
}
