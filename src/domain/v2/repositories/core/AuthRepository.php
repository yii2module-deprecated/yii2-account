<?php

namespace yii2module\account\domain\v2\repositories\core;

use Yii;
use yii2lab\core\domain\repositories\base\BaseCoreRepository;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;

class AuthRepository extends BaseCoreRepository implements AuthInterface {
	
	public $point = 'auth';
	
	public function authentication($login, $password, $ip = null) {
		$response = $this->post(null, compact('login', 'password'));
		return $this->forgeEntity($response, LoginEntity::class);
	}
	
	public function forgeEntity($data, $class = null) {
		/** @var LoginEntity $entity */
		$entity = parent::forgeEntity($data, $class);
		if(empty($entity->status)) {
			$entity->status = Yii::$domain->account->login->defaultStatus;
		}
		return $entity;
	}
}