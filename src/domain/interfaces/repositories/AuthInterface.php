<?php

namespace yii2module\account\domain\interfaces\repositories;

use yii2module\account\domain\entities\LoginEntity;

interface AuthInterface {
	
	/**
	 * @param $login
	 * @param $password
	 *
	 * @return LoginEntity
	 */
	public function authentication($login, $password);
	public function setToken($token);

}