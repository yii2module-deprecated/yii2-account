<?php

namespace yii2module\account\domain\v2\filters\auth;

use yii\filters\auth\AuthMethod;

class HttpTokenAuth extends AuthMethod
{
	/**
	 * @var string the HTTP authentication realm
	 */
	public $realm = 'api';


	/**
	 * @inheritdoc
	 */
	public function authenticate($user, $request, $response)
	{
		$authHeader = $request->getHeaders()->get('Authorization');
		if ($authHeader !== null) {
			$identity = $user->loginByAccessToken($authHeader, get_class($this));
			if ($identity === null) {
				$this->handleFailure($response);
			}
			return $identity;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function challenge($response)
	{
		$response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
	}
}
