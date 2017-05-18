<?php

use app\models\Customers;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Add Customers UI.
 *
 * @var View $this
 * @var Customers $customer
 */


$this->title = 'Register';
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to register:</p>

<?php

$V8Js = new \V8Js();
$V8Js->executeString(sprintf("%s; print(ReactDOMServer.renderToString(React.createElement(Register, %s)));",
     "var console = {warn: function(){}, error: print};var global = global || this, self = self || this, window = window || this;" .
     file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.js"). ';' .
     file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-with-addons.js"). ';' .
     file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom.js"). ';' .
     file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom-server.js"). ';' .
     file_get_contents(Yii::getAlias('@webroot/js/register.js')), json_encode(['customer' => $customer, '_csrf' => Yii::$app->request->getCsrfToken()])));

/*$form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]);

echo $form->field($customer, 'name')->hint('Please enter your name')->label('Name');
echo $form->field($customer, 'email');
echo $form->field($customer, 'password')->passwordInput();
echo $form->field($customer, 'password_repeat')->passwordInput();*/

?>

    <!--div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
            <?= Html::a('Login', '/web/site/login') ?>
        </div>
    </div-->

    <?php //ActiveForm::end(); ?>
</div>
