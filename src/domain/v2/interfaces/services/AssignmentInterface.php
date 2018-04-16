<?php

namespace yii2module\account\domain\v2\interfaces\services;

interface AssignmentInterface {
	
	public function allAssignments($userId);
	public function assignRole($userId, $role);
	public function revokeRole($userId, $role);
	public function revokeAllRoles($userId);
	public function isHasRole($userId, $roleName);
	public function allUserIdsByRole($roleName);

}