<?php

namespace yii2module\account\domain\v2\repositories\base;

use yii\web\NotFoundHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;
use yii2lab\domain\repositories\BaseRepository;

class BaseAuthRepository extends BaseRepository implements AuthInterface {
	
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
		try {
			$securityEntity = $this->domain->repositories->security->validatePassword($loginEntity->id, $password);
		} catch(UnprocessableEntityHttpException $e) {
			return false;
		}
		if($securityEntity->token == null) {
			$securityEntity = $this->domain->repositories->security->generateTokenById($loginEntity->id);
		}
		$loginEntity->security = $securityEntity;
		return $loginEntity;
	}
	
}