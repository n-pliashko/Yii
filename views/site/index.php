<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <?php if (!Yii::$app->user->isGuest): ?>
            <p><a class="btn btn-lg btn-success" href="/web/site/ulists">View list of users</a></p>
        <?php endif;?>
    </div>

    <div class="body-content">
    </div>
</div>
