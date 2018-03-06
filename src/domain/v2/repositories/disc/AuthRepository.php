<?php

namespace yii2module\account\domain\v2\repositories\disc;

use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;
use yii2lab\domain\repositories\BaseRepository;
use Yii;

class AuthRepository extends BaseRepository implements AuthInterface {

	public function authentication($login, $password) {
		$user = $this->domain->getRepositories()->login->oneByLogin($login);
		if(empty($user)) {
			return false;
		}
		if(!Yii::$app->security->validatePassword($password, $user->password_hash)) {
			return false;
		}
		return $user;
	}
	
	public function setToken($token) {
	
	}

}