<?php

namespace yii2module\account\domain\v2\repositories\core;

use Yii;
use yii2lab\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2lab\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;

class AuthRepository extends BaseCoreRepository implements AuthInterface {
	
	public $point = 'auth';

	public function authentication($login, $password, $otp = null, $ip = null) {
		$response = $this->post(null, compact('login', 'password', 'otp'), [ClientHelper::IP_HEADER_KEY => $ip]);
		Yii::warning('response answer '$response->content,__METHOD__);
		$entity = $this->forgeEntity($response, LoginEntity::class);
		return $entity;
	}
	
	public function forgeEntity($data, $class = null) {
		/** @var LoginEntity $entity */
		$entity = parent::forgeEntity($data, $class);
		if(empty($entity->status)) {
			$entity->status = \App::$domain->account->login->defaultStatus;
		}
		return $entity;
	}
}