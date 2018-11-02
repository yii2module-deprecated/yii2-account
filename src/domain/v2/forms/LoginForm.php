<?php

namespace yii2module\account\domain\v2\forms;

use Yii;
use yii2lab\domain\base\Model;
use yii2module\lang\domain\helpers\LangHelper;

class LoginForm extends Model
{
	
	const SCENARIO_SIMPLE = 'SCENARIO_SIMPLE';
	
	public $login;
	public $password;
	public $email;
	public $role;
	public $status;
	public $tokenType;
	public $rememberMe = true;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['login', 'password', 'tokenType'], 'trim'],
			[['login', 'password'], 'required'],
			['email', 'email'],
			//['login', 'match', 'pattern' => '/^[0-9_]{11,13}$/i', 'message' => Yii::t('account/registration', 'login_not_valid')],
			//['login', LoginValidator::class],
			'normalizeLogin' => ['login', 'normalizeLogin'],
			[['password'], 'string', 'min' => 8],
			['rememberMe', 'boolean'],
		    [['status'], 'safe'],
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_DEFAULT => [
				'login',
				'password',
				'email',
				'role',
				'status',
				'rememberMe',
				'tokenType',
			],
			self::SCENARIO_SIMPLE => [
				'login',
				'password',
				'tokenType',
			],
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
		//$this->$attribute = LoginHelper::pregMatchLogin($this->$attribute);
		$isValid = \App::$domain->account->login->isValidLogin($this->$attribute);
		if($isValid) {
			$this->$attribute = \App::$domain->account->login->normalizeLogin($this->$attribute);
		} else {
			$this->addError($attribute, LangHelper::extract(['account/login', 'not_valid']));
		}
	}
	
}
