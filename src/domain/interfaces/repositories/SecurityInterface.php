<?php

namespace yii2module\account\domain\interfaces\repositories;

interface SecurityInterface {
	
	public function changePassword($password, $newPassword);
	public function changeEmail($password, $email);

}