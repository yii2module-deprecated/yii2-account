<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\services\LoginInterface;
use yii2module\account\domain\v2\forms\LoginForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii2woop\common\domain\account\v2\helpers\UserHelper;

/**
 * Class LoginService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property \yii2module\account\domain\v2\interfaces\repositories\LoginInterface $repository
 * @property \yii2module\account\domain\v2\Domain $domain
 */
class LoginService extends ActiveBaseService implements LoginInterface {

	public $relations = [];
	public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList;
	
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
	
	public function create($data) {
		//$data['role'] = !empty($data['role']) ? $data['role'] : RoleEnum::UNKNOWN_USER;
		$data['email'] = !empty($data['email']) ? $data['email'] : 'api@wooppay.com';
        Helper::validateForm(LoginForm::class, $data);
		
		try {
			$user = $this->repository->oneByLogin($data['login']);
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
			throw new UnprocessableEntityHttpException($error);
		} catch(NotFoundHttpException $e) {
			
			//$data['roles'] = $data['role'];
			/** @var LoginEntity $loginEntity */
			$loginEntity = $this->domain->factory->entity->create($this->id, $data);
			if(empty($loginEntity->roles)) {
				$loginEntity->roles = [
					$this->defaultRole
				];
			}
			$this->repository->insert($loginEntity);
			
			/*if(!empty($loginEntity->id)) {
				
				$this->domain->security->create([
					'id' => $loginEntity->id,
					'email' => $data['email'],
					'password' => $data['password'],
				]);
				if (!empty($data['role'])){
					$role = ArrayHelper::toArray($data['role']);
					foreach ($role as $item){
						Yii::$domain->rbac->assignment->assign($item, $loginEntity->id);
					}
				} else {
					Yii::$domain->rbac->assignment->assign($this->defaultRole, $loginEntity->id);
				}
			}*/
			return $loginEntity;
		}
	}
	
	public function isForbiddenByStatus($status) {
	    if(empty($this->forbiddenStatusList)) {
			return false;
		}
		return in_array($status, $this->forbiddenStatusList);
	}
	
	// todo: перенести в домен услуг
	/*public function getAvailableServices() {
		return $this->repository->getAvailableServices();
	}*/
	
}
