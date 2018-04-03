<?php

namespace yii2module\account\console\forms;

use Yii;
use yii2module\account\domain\v1\helpers\LoginHelper;
use yii\base\Model;
use yii2module\account\domain\v1\validators\LoginValidator;
use domain\v1\account\models\Subjects;
use domain\v1\account\models\User;
use domain\v1\account\models\TypeSubject;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use common\helpers\RBACRoles;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password',], 'trim'],
            [['login', 'password',], 'required'],
            [['password'], 'validatePassword'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'         => Yii::t('main', 'login'),
            'rememberMe'        => Yii::t('main', 'remember_me'),
            'password'        => Yii::t('main', 'password'),
        ];
    }

    public function normalizeLogin($attribute)
    {
        $this->$attribute = LoginHelper::getPhone($this->$attribute);
    }
    
    public function addErrorsFromException($e) {
        foreach($e->getErrors() as $error) {
            if(!empty($error)) {
                $this->addError($error['field'], $error['message']);
            }
        }
    }
    
    /**
     * Внутренний валидатор пароля
     */
    public function validatePassword(){
        if (\Yii::$domain->account->manager->signIn($this->login, $this->password) === false) {
            $this->addError('password', \Yii::t('app', 'Неверный логин или пароль'));
        }
    }
}
