<?php
use yii\widgets\ListView;
$this->title = 'List of users';
$this->params['breadcrumbs'][] = $this->title;

echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list',
    'layout' => "<h1>All Users</h1><hr>{items}</br><hr>{summary}{pager}",
]);