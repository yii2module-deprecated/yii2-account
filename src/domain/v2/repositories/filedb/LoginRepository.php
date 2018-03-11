<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\domain\BaseEntity;
use yii2module\account\domain\v2\helpers\LoginEntityFactory;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class LoginRepository extends ActiveFiledbRepository implements LoginInterface {
	
	public function tableName()
	{
		return 'user';
	}
	
	public function fieldAlias() {
		return [
			'name' => 'username',
			'token' => 'auth_key',
		];
	}
	
	public function oneById($id, Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->removeParam('where');
		try {
			$q = clone $query;
			$q->where('id', $id);
			$one = $this->one($q);
		} catch(NotFoundHttpException $e) {
			$q = clone $query;
			$q->where('login', $id);
			$one = $this->one($q);
		}
		return $one;
	}

	public function isExistsByLogin($login) {
		return $this->isExists(['login' => $login]);
	}
	
	public function oneByLogin($login) {
		$query = Query::forge();
		$query->where('login', $login);
		return $this->one($query);
	}

	public function oneByToken($token, $type = null) {
		$query = Query::forge();
		$query->where('token', $token);
		return $this->one($query);
	}
	
	public function forgeEntity($user, $class = null)
	{
		if(empty($user)) {
			return null;
		}
		if(ArrayHelper::isIndexed($user)) {
			$collection = [];
			foreach($user as $item) {
				$collection[] = $this->forgeEntity($item, $class);
			}
			return $collection;
		}
		$user = ArrayHelper::toArray($user);
		$user['roles'] = explode(',', $user['role']);
		$user = $this->alias->decode($user);
		return LoginEntityFactory::forgeLoginEntity($user);
	}
	
	public function insert(BaseEntity $entity) {
		// TODO: Implement insert() method.
	}

	public function allByRole($role) {
		return [];
	}
	
	public function oneByRole($role) {
		// TODO: Implement oneByRole() method.
	}
	
	public function assignRole($id, $role) {
		// TODO: Implement assignRole() method.
	}
	
	public function revokeRole($id, $role) {
		// TODO: Implement revokeRole() method.
	}
	
	public function revokeAllRoles($id) {
		// TODO: Implement revokeAllRoles() method.
	}
	
	public function allAssignments($id) {
		// TODO: Implement allAssignments() method.
	}
	
	public function isHasRole($id, $role) {
		// TODO: Implement isHasRole() method.
	}
}