<?php

namespace yii2module\account\domain\v2\interfaces\services;

interface AuthInterface {

	public function authentication($login, $password);
	public function authenticationFromWeb($login, $password, $rememberMe = false);
	public function authenticationByToken($token, $type = null);
	public function logout();
	public function denyAccess();
	public function breakSession();

}