<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;

interface SecurityInterface extends CrudInterface {
	
	public function changePassword($password, $newPassword);
	public function changeEmail($password, $email);

}