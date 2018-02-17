<?php

namespace yii2module\account\domain\repositories\tps;

use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\repositories\TpsRepository;
use yii2module\account\domain\helpers\LoginEntityFactory;
use yii2module\account\domain\interfaces\repositories\LoginInterface;
use yii2woop\generated\enums\SubjectType;
use yii2woop\generated\transport\TpsCommands;

class LoginRepository extends TpsRepository implements LoginInterface {
	
	public function fieldAlias() {
		return [
			'id' => 'subject_id',
			'token' => 'authToken',
		];
	}
	
	public function isExistsByLogin($login) {
		try {
			$this->oneByLogin($login);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneByLogin($login) {
		$userList = $this->allByLogin($login);
		if(empty($userList)) {
			throw new NotFoundHttpException;
		}
		$user = $userList[0];
		return $this->forgeEntity($user);
	}
	
	public function oneById($id) {
		$userList = $this->allById($id);
		if(empty($userList)) {
			$userList = $this->allByLogin($id);
		}
		if(empty($userList)) {
			throw new NotFoundHttpException;
		}
		$user = $userList[0];
		return $this->forgeEntity($user);
	}
	
	public function oneByToken($token, $type = null) {
		try {
			$request = TpsCommands::getCurrentSubjectData();
			$user = $request->send();
			if($user) {
				return $this->forgeEntity($user);
			}
		} catch(Exception $e) {
		}
		return false;
	}
	
	public function insert(BaseEntity $entity) {
		//$data = $entity->toArray();
		$request = TpsCommands::insertSubject($entity->login, Yii::$app->request->getUserIP(), $entity->password, SubjectType::USER_UNIDENT, $entity->email);
		$request->send();
	}
	
	protected function allByLogin($login) {
		$login = ArrayHelper::toArray($login);
		try {
			$request = TpsCommands::searchSubject(null, $login);
			$data = $request->send();
			return $this->forgeUserListFromTpsResponse($data);
		} catch(Exception $e) {
		}
		return [];
	}
	
	protected function allById($ids) {
		$ids = ArrayHelper::toArray($ids);
		foreach($ids as &$id) {
			$id = intval($id);
		}
		try {
			$request = TpsCommands::searchSubject($ids);
			$data = $request->send();
			return $this->forgeUserListFromTpsResponse($data);
		} catch(Exception $e) {
		}
		return [];
	}
	
	protected function forgeUserListFromTpsResponse($data) {
		if(!empty($data['values'])) {
			$userList = [];
			foreach($data['values'] as $user) {
				$user = array_combine($data['keys'], $user);
				$userList[] = $user;
			}
			return $userList;
		}
		return [];
	}
	
	public function forgeEntity($user, $class = null) {
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		$user = $this->alias->decode($user);
		return LoginEntityFactory::forgeLoginEntity($user);
	}
	
	public function getAvailableServices() {
		$serviceIds = [];
		$currentUser = TpsCommands::getCurrentSubjectData()->send();
		$subject = Yii::$app->account->login->oneByLogin($currentUser['parent_login']);
		$availableServices = TpsCommands::getListAvailableServiceForSubject(null, $subject->id)->send();
		if(empty($availableServices['values'])) {
			return null;
		}
		foreach($availableServices['values'] as $service) {
			$service = Yii::$app->service->service->oneById($service[0]);
			array_push($serviceIds, [
				'id' => $service->id,
				'name' => $service->name,
			]);
		}
		return $serviceIds;
	}
	
}