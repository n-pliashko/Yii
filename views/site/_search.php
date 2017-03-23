<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">
    <?php $form = ActiveForm::begin([
        'action' => ['customers'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'id') ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'email') ?>
    <?php echo '<label class="control-label">Created</label><br>';?>
    <?php echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'created',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
                'class' => 'form-control'
        ],
    ]);?>
    <?php /*echo DateTimePicker::widget([
        'name'=> 'created',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'value' => $model->created,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-M-yyyy'
        ]
    ]); */?>
    <?php echo $form->field($model, 'role') ?>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>