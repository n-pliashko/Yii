<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= Html::jsFile('http://code.jquery.com/jquery-1.9.1.min.js'); ?>
    <?= Html::jsFile('//canjs.com/release/2.0.5/can.jquery.js'); ?>
    <?= Html::jsFile('//canjs.com/release/2.1.4/can.fixture.js'); ?>
    <?= Html::jsFile('https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js'); ?>
    <?= Html::jsFile('https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js'); ?>
    <?= Html::jsFile("https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"); ?>
    <?= Html::jsFile("https://cdnjs.cloudflare.com/ajax/libs/remarkable/1.6.2/remarkable.min.js"); ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Test Yii',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
    <?php
    $data =
        array('data' => array(
            array(1, 2, 3),
            array(4, 5, 6),
            array(7, 8, 9)
        ));


    $react_source = implode("\n", [
        file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.24.2/babel.js"),
        file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-with-addons.js"),
        file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.js"),
        //file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"),
       // file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom.js"),
        file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom-server.js")
    ]);

    $react_app = implode("\n", [
        file_get_contents(Yii::getAlias("@webroot").'/js/test.jsx')
    ]);

    $react = array();
    // stubs, react
    $react[] = "var console = {warn: function(){}, error: print}";
    $react[] = "var global = global || this, self = self || this, window = window || this";
    $react[] = $react_source;
    $react[] = "var React = global.React";
    $react[] = "var ReactDOMServer = global.ReactDOMServer";
    $react[] = $react_app;

    $concatenated = implode(";\n", $react);

    $v8 = new \V8Js();

    $v8->executeString($concatenated);

    $js = $v8->executeString(sprintf("ReactDOMServer.renderToString(React.createElement(%s))", 'CommentBox'));
    ?>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
