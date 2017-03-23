<?php
namespace app\models;

use yii\helpers\VarDumper;
use yii\rbac\Rule;

class UserRule extends Rule
{
    public $name = 'isUser';

    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post'] == $user : false;
    }
}