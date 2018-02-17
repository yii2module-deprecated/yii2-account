<?php

namespace yii2module\account\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2module\account\domain\helpers\ConfirmHelper;
use yii2module\account\domain\helpers\LoginHelper;
use yii2module\account\domain\validators\LoginValidator;

class ConfirmEntity extends BaseEntity {

	protected $login;
	protected $action;
	protected $code;
	protected $created_at;
	
	public function rules()
	{
		return [
			[['login', 'action', 'code'], 'trim'],
			[['login', 'action', 'code'], 'required'],
			['login', LoginValidator::className()],
			//'normalizeLogin' => ['login', 'normalizeLogin'],
			[['code'], 'string', 'length' => 6],
		];
	}
	
	public function getActivationCode() {
		if(empty($this->code)) {
			$this->code = ConfirmHelper::generateCode();
		}
		return $this->code;
	}
	
	public function setLogin($value) {
		$this->login = LoginHelper::getPhone($value);
	}
	
}
