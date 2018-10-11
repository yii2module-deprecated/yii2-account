<?php

namespace yii2module\account\domain\v2\filters\token;

class DefaultFilter extends BaseTokenFilter {

	public function run() {
	    $loginEntity = \App::$domain->account->repositories->login->oneByToken($this->token);
	    $this->setData($loginEntity);
	}
	
	public function auth($body, $ip) {
		$loginEntity = \App::$domain->account->repositories->auth->authentication($body['login'], $body['password'], $ip);
		$loginEntity->token = $this->forgeToken($loginEntity->token);
		return $loginEntity;
	}
	
}
