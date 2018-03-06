<?php

namespace yii2module\account\domain\v2\entities;

use yii2lab\domain\BaseEntity;
use yii2module\account\domain\v2\helpers\ConfirmHelper;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\validators\LoginValidator;

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
			['login', LoginValidator::class],
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
