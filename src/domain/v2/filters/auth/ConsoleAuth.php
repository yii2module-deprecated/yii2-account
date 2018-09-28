<?php

namespace yii2module\account\domain\v2\filters\auth;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\extension\console\helpers\input\Enter;
use yii2lab\extension\console\helpers\Output;
use yii2module\account\domain\v2\forms\LoginForm;
use yii2module\account\domain\v2\helpers\AuthHelper;
use yii2module\error\domain\helpers\UnProcessibleHelper;

class ConsoleAuth extends AuthMethod
{
	/**
	 * @var string the HTTP authentication realm
	 */
	public $realm = 'api';

	
	public function beforeAction($action) {
		$response = $this->response ?: Yii::$app->getResponse();
		
		try {
			$identity = $this->authenticate(
				null,
				$this->request ?: Yii::$app->getRequest(),
				$response
			);
		} catch (UnauthorizedHttpException $e) {
			
			
			if ($this->isOptional($action)) {
				return true;
			}
			
			throw $e;
		}
		
		if ($identity !== null || $this->isOptional($action)) {
			return true;
		}
		
		$this->challenge($response);
		$this->handleFailure($response);
		
		return false;
	}
	
	private function auth() {
		$identity = null;
		$default = null;
		$form = new LoginForm;
		$data = Enter::form($form, $default, LoginForm::SCENARIO_SIMPLE);
		try {
			$identity = \App::$domain->account->auth->authentication($data['login'], $data['password']);
		} catch(UnprocessableEntityHttpException $e) {
			$errors = UnProcessibleHelper::getFirstErrors($e->getErrors());
			Output::arr($errors, 'Validation error');
			$this->auth();
		}
		Yii::$app->cache->set('identity', $identity);
		return $identity;
	}
	
	/**
	 * @inheritdoc
	 */
	public function authenticate($user, $request, $response)
	{
		$identity = null;
		if(Yii::$app->user->isGuest) {
			//Yii::$app->cache->set('identity', null);
			$identity = Yii::$app->cache->get('identity');
			if(!$identity) {
				$identity = $this->auth();
			}
			/*if(!$identity instanceof IdentityInterface) {
				$identity = new LoginEntity($identity);
			}*/
			Yii::$app->user->setIdentity($identity);
			AuthHelper::setToken($identity->token);
		}
		return $identity;
	}

	/**
	 * @inheritdoc
	 */
	public function challenge($response)
	{
	}
}
