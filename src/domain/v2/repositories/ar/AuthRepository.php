<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii\web\NotFoundHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;
use yii2lab\domain\repositories\BaseRepository;

class AuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password) {
		try {
			/** @var LoginEntity $loginEntity */
			$loginEntity = $this->domain->getRepositories()->login->oneByLogin($login);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($loginEntity)) {
			return false;
		}
		/** @var SecurityEntity $securityEntity */
		$securityEntity = $this->domain->repositories->security->validatePassword($loginEntity->id, $password);
		if(!$securityEntity) {
			return false;
		}
		$loginEntity->security = $securityEntity;
		return $loginEntity;
	}
	
	public function setToken($token) {
	
	}
	
}