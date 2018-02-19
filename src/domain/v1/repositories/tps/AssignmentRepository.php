<?php

namespace yii2module\account\domain\v1\repositories\tps;

use Yii;
use yii\rbac\Assignment;
use yii\web\NotFoundHttpException;
use yii2lab\domain\repositories\TpsRepository;

class AssignmentRepository extends TpsRepository {
	
	protected $modelClass = 'yii2module\account\domain\v1\models\UserAssignment';
	protected $primaryKey = null;
	
	public function uniqueFields() {
		return [];
	}
	
	public function revokeOneRole($userId, $role) {
		return [];
	}
	
	public function revokeAllRoles($userId) {
		return [];
	}
	
	public function oneAssign($userId, $itemName) {
		return [];
	}
	
	public function allByUserId($userId) {
		return [];
	}
	
	public function allRoleNamesByUserId($userId) {
		if(empty($userId)) {
			return [];
		}
		if($userId != Yii::$app->user->identity->getId()) {
			return [];
		}
		return Yii::$app->user->identity->roles;
	}
	
	public function allAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		$roles = $this->allRoleNamesByUserId($userId);
		return $this->forgeAssignments($userId, $roles);
	}
	
	public function assignRole($userId, $role) {
		
		return [];
	}
	
	public function revokeRole($userId, $role) {
		return [];
	}
	
	public function isHasRole($userId, $role) {
		try {
			$entity = $this->oneAssign($userId, $role);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function allUserIdsByRole($role) {
		return [];
	}
	
	public function allByRole($role) {
		return [];
	}
	
	private function forgeAssignment($userId, $roleName) {
		$assignment = new Assignment([
			'userId' => $userId,
			'roleName' => $roleName,
			'createdAt' => 1486774821,
		]);
		return $assignment;
	}
	
	private function forgeAssignments($userId, $roleNames) {
		if(empty($roleNames)) {
			return [];
		}
		$assignments = [];
		foreach($roleNames as $roleName) {
			$assignments[ $roleName ] = $this->forgeAssignment($userId, $roleName);
		}
		return $assignments;
	}
	
	private function getId($id) {
		return is_object($id) ? $id->id : $id;
	}
}