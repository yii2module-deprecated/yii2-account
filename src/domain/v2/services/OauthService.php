<?php

namespace yii2module\account\domain\v2\services;

use yii\authclient\BaseOAuth;
use yii\web\NotFoundHttpException;
use yii2lab\domain\enums\Driver;
use yii2lab\domain\helpers\factory\RepositoryFactoryHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\services\OauthInterface;
use yii2lab\domain\services\base\BaseService;

/**
 * Class OauthService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class OauthService extends BaseService implements OauthInterface {
	
	/** @var \yii2module\account\domain\v2\interfaces\repositories\LoginInterface */
	private $_arLoginRepository;
	
	public function init() {
		/** @var \yii2module\account\domain\v2\interfaces\repositories\LoginInterface $arLoginRepository */
		$this->_arLoginRepository = RepositoryFactoryHelper::createObject('login', Driver::ACTIVE_RECORD, \App::$domain->account);
		parent::init();
	}
	
	public function oneById($id) : LoginEntity {
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->_arLoginRepository->oneById($id);
		$loginEntity->roles = [
			'rOauth',
			'rUser',
		];
		return $loginEntity;
	}
	
	public function forgeAccount(BaseOAuth $client) : LoginEntity {
		try {
			$loginEntity = $this->oneByClient($client);
		} catch(NotFoundHttpException $e) {
			$loginEntity = $this->insert($client);
		}
		return $loginEntity;
	}
	
	private function oneByClient(BaseOAuth $client) : LoginEntity {
		$login = $this->generateLogin($client);
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->_arLoginRepository->oneByLogin($login);
		return $loginEntity;
	}
	
	private function insert(BaseOAuth $client) : LoginEntity {
		$loginEntity = new LoginEntity;
		$loginEntity->login = $this->generateLogin($client);
		$this->_arLoginRepository->insert($loginEntity);
		return $loginEntity;
	}
	
	private function generateLogin(BaseOAuth $client) : string {
		$login = $client->userAttributes['login'] . '@' . $client->getId();
		return $login;
	}
	
}
