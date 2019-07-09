<?php

namespace yii2module\account\domain\v2\filters\token;

use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\filters\login\LoginPhoneValidator;
use yii2module\account\domain\v2\services\RegistrationService;

class DefaultFilter extends BaseTokenFilter {
	
	public function authByToken($token) {
		$loginEntity = \App::$domain->account->repositories->login->oneByToken($token);
		return $loginEntity;
	}
	
	public function login($body, $ip) {
		$login = LoginPhoneValidator::isCharInLogin($body['login']);
		$loginEntity = \App::$domain->account->repositories->auth->authentication($login, $body['password'], $body['otp'], $ip);
		if($loginEntity instanceof LoginEntity) {
            $loginEntity->token = $this->forgeToken($loginEntity->token);
            return $loginEntity;
        }
		return null;
	}

	// временный костыль
//	public function isCharInLogin($login) {
//		if(preg_match("/^[a-zA-Z]+/", $login)){
//			return $login;
//		} else {
//			return RegistrationService::checkPrefix().$login;
//		}
//	}

}
