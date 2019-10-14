<?php

namespace yii2module\account\domain\v2\forms;

use Yii;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\filters\login\LoginPhoneValidator;
use yii2module\account\domain\v2\validators\LoginValidator;
use yii2lab\domain\base\Model;

class RestorePasswordForm extends Model {
	
	public $login;
	public $activation_code;
	public $password;
	public $email;
	public $url;
	
	const SCENARIO_REQUEST = 'request';
	const SCENARIO_CHECK = 'check';
	const SCENARIO_CONFIRM = 'confirm';
	const SCENARIO_RESEND_CODE ='resend-code';

    public function rules() {
		return [
			[['login', 'password', 'activation_code', 'email', 'url'], 'trim'],
			[['login', 'password', 'activation_code',  'url'], 'required'],
			['login', 'getValidator'],
			[['password'], 'string', 'min' => 4],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'login' => Yii::t('account/main', 'login'),
			'password' => Yii::t('account/main', 'password'),
			'activation_code' => Yii::t('account/main', 'activation_code'),
            'email' => Yii::t('account/main', 'email'),
            'url' => Yii::t('account/main', 'url')
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_REQUEST => ['login', 'email'],
			self::SCENARIO_CHECK => ['login', 'activation_code'],
			self::SCENARIO_CONFIRM => ['login', 'activation_code', 'password'],
            self::SCENARIO_RESEND_CODE => ['login', 'email', 'url']
		];
	}

	public function getValidator() {
		if(LoginHelper::validate($this->login)) {
			return LoginPhoneValidator::class;
		} else {
			return LoginValidator::class;
		}
	}
	/**
	 * @param mixed $url
	 */
	public function setUrl($url): void
	{
		$link = $url . '/site/confirmKey' . '/key/_reset_hash_' . '/login/_login_';
		$link = str_replace('_reset_hash_', '%reset_hash%', $link);
		$link = str_replace('_login_', '%login%', $link);
		$this->url = $link;
	}
}
