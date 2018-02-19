<?php

namespace yii2module\account\domain\v1\forms;

use Yii;
use yii2module\account\domain\v1\helpers\LoginHelper;
use yii\base\Model;
use yii2module\account\domain\v1\validators\LoginValidator;

class LoginForm extends Model
{
	public $login;
	public $password;
	public $email;
	public $role;
	public $status;
	public $rememberMe = true;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['login', 'password'], 'trim'],
			[['login', 'password'], 'required'],
			['email', 'email'],
			//['login', 'match', 'pattern' => '/^[0-9_]{11,13}$/i', 'message' => Yii::t('account/registration', 'login_not_valid')],
			//['login', LoginValidator::className()],
			'normalizeLogin' => ['login', 'normalizeLogin'],
			[['password'], 'string', 'min' => 8],
			['rememberMe', 'boolean'],
		    [['status'], 'safe'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'login' 		=> Yii::t('account/main', 'login'),
			'password' 		=> Yii::t('account/main', 'password'),
			'rememberMe' 		=> Yii::t('account/auth', 'remember_me'),
		];
	}

	public function normalizeLogin($attribute)
	{
		$this->$attribute = LoginHelper::pregMatchLogin($this->$attribute);
	}
	
	public function addErrorsFromException($e) {
		foreach($e->getErrors() as $error) {
			if(!empty($error)) {
				$this->addError($error['field'], $error['message']);
			}
		}
	}
}
