<?php

namespace yii2module\account\domain\v2\interfaces\services;

interface AssignmentInterface {
	
	public function allAssignments($id);
	public function assignRole($id, $role);
	public function revokeRole($id, $role);
	public function revokeAllRoles($id);
	public function isHasRole($userId, $roleName);
	public function allUserIdsByRole($roleName);

}