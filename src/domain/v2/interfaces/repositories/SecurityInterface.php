<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

interface SecurityInterface {
	
	public function changePassword($password, $newPassword);
	public function changeEmail($password, $email);

}