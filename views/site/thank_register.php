<?php
use yii\helpers\Html;
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">Hello, <?= Html::encode($customer->name); ?>. Thank you for registration.</p>
    </div>

    <div class="body-content">
        <div class="row">
            <p><?= Html::a('View all users &raquo;', '/web/site/ulists', ['class' => "btn btn-default"]) ?></p>
        </div>
    </div>
</div>
