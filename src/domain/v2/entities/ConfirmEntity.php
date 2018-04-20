<?php

namespace yii2module\account\domain\v2\entities;

use paulzi\jsonBehavior\JsonValidator;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\TimeValue;
use yii2module\account\domain\v2\helpers\ConfirmHelper;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\validators\LoginValidator;

/**
 * Class ConfirmEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property $login
 * @property $action
 * @property $code
 * @property $data
 * @property $expire
 * @property $created_at
 */
class ConfirmEntity extends BaseEntity {

	protected $login;
	protected $action;
	protected $code;
	protected $data;
	protected $expire;
	protected $created_at;
	
	public function fieldType() {
		return [
			'created_at' => TimeValue::class,
		];
	}
	
	public function rules()
	{
		return [
			[['login', 'action', 'code'], 'trim'],
			[['login', 'action', 'code', 'expire'], 'required'],
			[['expire'], 'integer'],
			['login', LoginValidator::class],
			//'normalizeLogin' => ['login', 'normalizeLogin'],
			[['code'], 'string', 'length' => 6],
			[['data'], JsonValidator::class],
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
