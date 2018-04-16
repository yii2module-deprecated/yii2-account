<?php

namespace yii2module\account\domain\v2\services;

use yii2module\account\domain\v2\interfaces\services\AssignmentInterface;
use yii2lab\domain\services\ActiveBaseService;

/**
 * Class AssignmentService
 *
 * @package yii2module\account\domain\v2\services
 * @property \yii2module\account\domain\v2\interfaces\repositories\AssignmentInterface $repository
 */
class AssignmentService extends ActiveBaseService implements AssignmentInterface {

	public function allAssignments($userId) {
		return $this->repository->allAssignments($userId);
	}
	
	public function assignRole($userId, $role) {
		return $this->repository->assignRole($userId, $role);
	}
	
	public function revokeRole($userId, $role) {
		return $this->repository->revokeRole($userId, $role);
	}
	
	public function revokeAllRoles($userId) {
		return $this->repository->revokeAllRoles($userId);
	}
	
	public function isHasRole($userId, $roleName) {
		return $this->repository->isHasRole($userId, $roleName);
	}
	
	public function allUserIdsByRole($roleName) {
		return $this->repository->allUserIdsByRole($roleName);
	}
	
}
