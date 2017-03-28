<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customer-index">

        <h1><?= Html::encode($this->title); ?></h1>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>

       
    <?php if (Yii::$app->user->can('create')): ?>
         <p>
            <?= Html::a('Create Customers',['create'],['class' => 'btn btn-success']); ?>
        </p>
    <?php endif; ?>

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'email:email',
            'role',
            'created',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Yii::$app->user->can('updateOwn', ['post' => $key]) || Yii::$app->user->can('update');
                    },
                    'delete' => function ($model, $key, $index) {
                        return Yii::$app->user->can('delete');
                    }
                ]

            ],
            ['class' => 'yii\grid\SerialColumn']
        ],
    ]); ?>
</div>
