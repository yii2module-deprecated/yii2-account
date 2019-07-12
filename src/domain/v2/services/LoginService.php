<?php

namespace yii2module\account\domain\v2\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\extension\common\helpers\InstanceHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\filters\login\LoginPhoneValidator;
use yii2module\account\domain\v2\filters\login\LoginValidator;
use yii2module\account\domain\v2\interfaces\LoginValidatorInterface;
use yii2module\account\domain\v2\interfaces\services\LoginInterface;
use yii2module\account\domain\v2\forms\LoginForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class LoginService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property \yii2module\account\domain\v2\interfaces\repositories\LoginInterface $repository
 * @property \yii2module\account\domain\v2\Domain $domain
 */
class LoginService extends BaseActiveService implements LoginInterface {

	public $relations = [];
	public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList;

	public function oneById($id, Query $query = null) {
		try {
			$loginEntity = parent::oneById($id, $query);
		} catch(NotFoundHttpException $e) {
			if($this->domain->oauth->isEnabled()) {
				$loginEntity = \App::$domain->account->oauth->oneById($id);
			} else {
				throw $e;
			}
		}
		return $loginEntity;
	}
	
	public function isExistsByLogin($login) {
		return $this->repository->isExistsByLogin($login);
	}
	
	/**
	 * @param $login
	 *
	 * @return \yii2module\account\domain\v2\entities\LoginEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login) {
		return $this->repository->oneByLogin($login);
	}
	
	public function isValidLogin($login) {
		return $this->getLoginValidator($login)->isValid($login);
	}
	
	public function normalizeLogin($login) {
		return $this->getLoginValidator($login)->normalize($login);
	}
	
	public function create($data) {
		$data['email'] = !empty($data['email']) ? $data['email'] : 'api@wooppay.com';
		Helper::validateForm(LoginForm::class, $data);
		$login = RegistrationService::checkPrefix().$data['login'];
		try {
			$this->repository->oneByLogin($login);
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
			throw new UnprocessableEntityHttpException($error);
		} catch(NotFoundHttpException $e) {

			/** @var LoginEntity $loginEntity */
			$loginEntity = $this->domain->factory->entity->create($this->id, $data);
			if(empty($loginEntity->roles)) {
				$loginEntity->roles = [
					$this->defaultRole
				];
			}
			$this->repository->insert($loginEntity);

			return $loginEntity;
		}
	}
	
	public function isForbiddenByStatus($status) {
	    if(empty($this->forbiddenStatusList)) {
			return false;
		}
		return in_array($status, $this->forbiddenStatusList);
	}
	
	/**
	 * @param $login
	 * @return array|object|string|LoginValidatorInterface
	 */
	private function getLoginValidator($login) {
		if(is_numeric($login)) {
			return InstanceHelper::ensure(LoginPhoneValidator::class, [], LoginValidatorInterface::class);
		} else {
			return InstanceHelper::ensure(LoginValidator::class, [], LoginValidatorInterface::class);
		}
	}
}
