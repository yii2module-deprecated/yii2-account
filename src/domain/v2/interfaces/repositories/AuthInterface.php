<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2module\account\domain\v2\entities\LoginEntity;

interface AuthInterface {
	
	/**
	 * @param string $login
	 * @param string $password
	 * @param null   $otp
	 * @param null   $ip
	 *
	 * @return LoginEntity
	 */
	public function authentication($login, $password, $otp = null, $ip = null);
	
}