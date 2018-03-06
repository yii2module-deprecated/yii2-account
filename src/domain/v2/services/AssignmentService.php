<?php

namespace yii2module\account\domain\v2\services;

use yii2module\account\domain\v2\interfaces\services\AssignmentInterface;
use yii2lab\domain\services\ActiveBaseService;

class AssignmentService extends ActiveBaseService implements AssignmentInterface {
	
	
	public function allAssignments($id) {
		return $this->repository->allAssignments($id);
	}
	
	public function assignRole($id, $role) {
		return $this->repository->assignRole($id, $role);
	}
	
	public function revokeRole($id, $role) {
		return $this->repository->revokeRole($id, $role);
	}
	
	public function revokeAllRoles($id) {
		return $this->repository->revokeAllRoles($id);
	}
	
	public function isHasRole($userId, $roleName) {
		return $this->repository->isHasRole($userId, $roleName);
	}
	
	public function allUserIdsByRole($roleName) {
		return $this->repository->allUserIdsByRole($roleName);
	}
	
}
