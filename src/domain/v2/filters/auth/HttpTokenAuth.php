<?php

namespace yii2module\account\domain\v2\filters\auth;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\Request;
use yii\web\Response;

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
		/** @var Request $request */
		$authHeader = $request->getHeaders()->get('Authorization');
		if ($authHeader !== null) {
			$identity = Yii::$domain->account->auth->authenticationByToken($authHeader, get_class($this));
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
		/** @var Response $response */
		$response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
	}
}
