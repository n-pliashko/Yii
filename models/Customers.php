<?php
namespace app\models;

use Yii;
use ReflectionClass;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\mssql\PDO;
use yii\db\QueryInterface;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\db\Query;

class Customers extends Model implements IdentityInterface
{
    public $id;
    public $password_repeat;
    public $auth_key;
    public $created;
    public $name;
    public $email;
    public $password;
    public $role;

    private $_oldAttributes = [];

    public static function tableName()
    {
        return 'customers';
    }

    public function getIsNewRecord() {
        return empty($this->id);
    }

    public function rules()
    {
        return [
            ['id', 'number'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'existEmail', 'on' => 'register'],
            ['name', 'string', 'max' => 50],
            ['password', 'string', 'min' => 8, 'max' => 255],
            ['auth_key', 'string', 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute'=>'password'],
            ['password_repeat', 'safe'],
            ['role', 'in', 'range' => array('user','admin')],
        ];
    }

    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->_oldAttributes[$name] = $this->$name;
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    public function getAllAttributes()
    {
        $attributes = parent::getAttributes();
        $tableSchema = Yii::$app->db->getTableSchema(static::tableName());
        $attr = array_fill_keys(array_values($tableSchema->getColumnNames()), null);
        return array_intersect_key($attributes, $attr);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function beforeSave()
    {
        if ($this->isAttributeChanged('password'))
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        if ($this->isNewRecord) {
            $this->auth_key = Yii::$app->security->generateRandomString($length = 20);
            $this->created = date('Y-m-d H:i:s');
            $this->role = 'user';
        }
    }

    public function isAttributeChanged($name, $identical = true)
    {
        if (isset($this->{$name}, $this->_oldAttributes[$name])) {
            if ($identical) {
                return $this->{$name} !== $this->_oldAttributes[$name];
            } else {
                return $this->{$name} != $this->_oldAttributes[$name];
            }
        } else {
            return isset($this->{$name}) && isset($this->_oldAttributes[$name]);
        }
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
        return !$rows ? null : new Customers($rows);
    }

    public function existEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!empty(static::findByEmail($this->email))) {
                $this->addError($attribute, 'User with such email already registry. Please login');
            }
        }
    }

    public function delete() {
        return Yii::$app->db->createCommand()->delete(static::tableName(), ['id' => $this->id])->execute();
    }

    public function save() {
        if ($this->validate()) {
            $this->beforeSave();
            $command = Yii::$app->db->createCommand();
            $attr = $this->getAllAttributes();
            if ($this->isNewRecord) {
                $result = $command->insert(self::tableName(), $attr)->execute();
                $last_id = Yii::$app->db->getLastInsertID();
                $this->setAttributes(static::findOne(['id' => $last_id])->getAttributes());
            } else {
                $result = $command->update(self::tableName(), $attr, ['id' => $this->id])->execute();
            }
            return $result;
        }
        return false;
    }

}