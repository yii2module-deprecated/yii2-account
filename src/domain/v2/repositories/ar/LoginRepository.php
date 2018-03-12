<?php

namespace yii2module\account\domain\v2\repositories\ar;

use Yii;
use yii\web\IdentityInterface;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\repositories\ActiveArRepository;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;

class LoginRepository extends ActiveArRepository implements LoginInterface {

	protected $modelClass = 'yii2module\account\domain\v2\models\User';
	
	public function uniqueFields() {
		return ['login'];
	}
	/*
	public function fieldAlias() {
		return [
			'token' => 'auth_key',
		];
	}
	*/
	public function isExistsByLogin($login) {
		return $this->isExists(['login' => $login]);
	}
	
	public function oneByRole($role) {
		$collection = $this->domain->repositories->assignment->allByRole($role);
		if(count($collection) < 1) {
			return null;
		}
		return $this->forgeEntity($collection[0]);
	}
	
	public function allByRole($role) {
		$collection = $this->domain->repositories->assignment->allByRole($role);
		$ids = ArrayHelper::getColumn($collection, 'user_id');
		return $this->allById($ids);
	}
	
	public function allById($id) {
		$models = $this->allModelsByCondition(['id' => $id]);
		return $this->forgeEntity($models);
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
	public function oneByToken($token, $type = null) {
		/** @var SecurityEntity $securityEntity */
		$securityEntity = $this->domain->repositories->security->oneByToken($token, $type);
		return $this->oneById($securityEntity->id);
	}
	
	public function insert(BaseEntity $loginEntity) {
		/** @var LoginEntity $loginEntity */
		$this->findUnique($loginEntity);
		/** @var IdentityInterface $model */
		$model = Yii::createObject(get_class($this->model));
		$model->id = $this->lastId() + 1;
		$model->login = $loginEntity->login;
		$model->status = $loginEntity->status !== null ? $loginEntity->status : $this->domain->login->defaultStatus;
		$this->saveModel($model);
		$loginEntity->id = $model->id;
	}
	
	private function lastId() {
		$model = $this->model->find()->orderBy(['id' => SORT_DESC])->one();
		return $model->id;
	}
	
	public function forgeEntity($user, $class = null)
	{
		if(empty($user)) {
			return null;
		}
		if(is_array($user) && ArrayHelper::isIndexed($user)) {
			$collection = [];
			foreach($user as $item) {
				$collection[] = $this->forgeEntity($item);
			}
			return $collection;
		}
		$user['roles'] = $this->domain->repositories->assignment->allRoleNamesByUserId($user['id']);
		$user = $this->alias->decode($user);
		return parent::forgeEntity($user);
	}

}