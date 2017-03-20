<?php
namespace app\models;

use yii\base\Model;
use yii\validators\EmailValidator;

class RegisterForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['email', 'password', 'password_repeat'], 'required'],
            ['email', 'validateEmail'],
            ['name', 'string', 'max' => 50],
            ['password', 'string', 'min' => 8, 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute'=>'password'],
            ['password_repeat', 'safe'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        $validator = new EmailValidator();
        if ($validator->validate($this->email, $error)) {
            if (!$this->hasErrors()) {
                if (!empty(Customer::findByEmail($this->email))) {
                    $this->addError($attribute, 'User with such email already registry. Please logged');
                }
            }
        } else {
            $this->addError($attribute, $error);
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Customer::findByEmail($this->email);
        }
        return $this->_user;
    }

    public function register()
    {
        if ($this->validate()) {
            $user = new Customer();
            $user->setAttributes((array)$this);
            return $user->save();
        }
        return false;
    }
}