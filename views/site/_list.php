<?php
use yii\helpers\Html;
use yii\i18n\Formatter;
?>
<div class="users-item">
    <h3><?= Html::encode($model->name) ?></h3>
    <?= Formatter::asEmail($model->email) ?> -
    <?= Html::encode($model->created); ?>
</div>