<?php

namespace yii2module\account\domain\v2\repositories\core;

use Yii;
use yii2lab\domain\repositories\BaseRepository;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\AssignmentHelper;
use yii2module\account\domain\v2\interfaces\repositories\AssignmentInterface;

class AssignmentRepository extends BaseRepository implements AssignmentInterface {
	
	public function revokeOneRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeOneRole() method.
	}
	
	public function revokeAllRoles($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeAllRoles() method.
	}
	
	public function oneAssign($userId, $itemName) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement oneAssign() method.
	}
	
	public function allByUserId($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allByUserId() method.
	}
	
	public function allRoleNamesByUserId($userId) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allRoleNamesByUserId() method.
	}
	
	public function allAssignments($userId) {
		if(empty($userId)) {
			return [];
		}
		/** @var LoginEntity $identity */
		$identity = Yii::$app->user->identity;
		if($identity->id == $userId) {
			return AssignmentHelper::forge($userId, $identity->roles);
		}
	}
	
	public function assignRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement assignRole() method.
	}
	
	public function revokeRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement revokeRole() method.
	}
	
	public function isHasRole($userId, $role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement isHasRole() method.
	}
	
	public function allUserIdsByRole($role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allUserIdsByRole() method.
	}
	
	public function allByRole($role) {
		exit('Not Implement of ' . __METHOD__);
		// TODO: Implement allByRole() method.
	}
	
}