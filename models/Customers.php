<?php
namespace app\models;

use Yii;
use ReflectionClass;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\QueryInterface;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\db\Query;

class Customers extends Model implements IdentityInterface
{
    public $id;
    public $password_repeat;
    public $rememberMe = true;
    public $auth_key;
    public $created;
    public $name;
    public $email;
    public $password;
    public $role;

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
            ['role', 'in', 'range' => array('user','admin')],
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function beforeSave($insert)
    {
        $return = parent::beforeSave($insert);
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
        throw new NotSupportedException('You саn only login by username/password pair for now.');
    }

    public static function findByEmail($email)
    {
       return static::findOne(['email' => $email]);
    }

    public static function findOne($array) {
        $rows = (new Query())
            ->from(self::tableName())
            ->where($array)
            ->createCommand()
            ->queryOne();
        $customer = new Customers();
        $customer->setAttributes($rows, false);
        return $customer;//new Customers($rows);
    }


    public function register()
    {
        if ($this->validate()) {
            return Yii::$app->db->createCommand()->insert(self::tableName(), $this->getAttributes())->execute();
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