<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Customer extends ActiveRecord implements IdentityInterface
{
    public $password_repeat;

    public static function tableName()
    {
        return 'customers';
    }

    public function rules()
    {
        return [
            ['id', 'number'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'existEmail'],
            ['name', 'string', 'max' => 50],
            ['password', 'string', 'min' => 8, 'max' => 255],
            ['auth_key', 'string', 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute'=>'password'],
            ['password_repeat', 'safe'],
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function beforeSave($insert)
    {
        $return = parent:: beforeSave($insert);
        if ($this->isAttributeChanged('password'))
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        if ($this->isNewRecord)
            $this->auth_key = Yii::$app->security->generateRandomString($length = 20);
        return $return;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password );
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('You сап only login Ьу username/password pair for now.');
    }

    public static function findByEmail($email)
    {
       return static::findOne(['email' => $email]);
    }

    public function register()
    {
        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }

    public function existEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!empty(static::findByEmail($this->email))) {
                $this->addError($attribute, 'User with such email already registry. Please logged');
            }
        }
    }

}