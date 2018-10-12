<?php

namespace yii2module\account\domain\v2\filters\token;

class DefaultFilter extends BaseTokenFilter {
	
	public function auth($token) {
		$loginEntity = \App::$domain->account->repositories->login->oneByToken($token);
		return $loginEntity;
	}
	
	public function login($body, $ip) {
		$loginEntity = \App::$domain->account->repositories->auth->authentication($body['login'], $body['password'], $ip);
		$loginEntity->token = $this->forgeToken($loginEntity->token);
		return $loginEntity;
	}
	
}
