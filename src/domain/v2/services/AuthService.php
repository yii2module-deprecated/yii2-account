<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\BaseService;
use yii2lab\helpers\ClientHelper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\forms\LoginForm;
use yii2module\account\domain\v2\helpers\AuthHelper;
use yii2module\account\domain\v2\helpers\TokenHelper;
use yii2module\account\domain\v2\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;

/**
 * Class AuthService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property \yii2module\account\domain\v2\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

    public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;
	
	public function authentication($login, $password, $ip = null) {
		if(empty($ip)) {
			$ip = ClientHelper::ip();
		}
		$body = compact(['login', 'password']);
		$body = Helper::validateForm(LoginForm::class, $body);
		try {
			$loginEntity = $this->repository->authentication($body['login'], $body['password'], $ip);
		} catch(NotFoundHttpException $e) {
			$loginEntity = false;
		}
		if(!$loginEntity instanceof LoginEntity || empty($loginEntity->id)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		$this->checkStatus($loginEntity);
		AuthHelper::setToken($loginEntity->token);
		return $loginEntity;
	}
	
	private function checkStatus(LoginEntity $entity)
	{
	    if (\App::$domain->account->login->isForbiddenByStatus($entity->status)) {
	        throw new ServerErrorHttpException(Yii::t('account/login', 'user_status_forbidden'));
	    }
	}

	public function getIdentity() {
		if(Yii::$app->user->isGuest) {
			\App::$domain->account->auth->loginRequired();
            $this->breakSession();

		}
		return Yii::$app->user->identity;
	}

	public function authenticationFromWeb($login, $password, $rememberMe = false) {
		$loginEntity = $this->authentication($login, $password);
		$this->login($loginEntity, $rememberMe);
	}

	public function login(LoginEntity $loginEntity, $rememberMe = false) {
        if(empty($loginEntity)) {
            return null;
        }
        $duration = $rememberMe ? $this->rememberExpire : 0;
        Yii::$app->user->login($loginEntity, $duration);
        AuthHelper::setToken($loginEntity->token);
    }

	public function authenticationByToken($token, $type = null) {
		if(empty($token)) {
			throw new InvalidArgumentException('Empty token');
		}
        $tokenArray = TokenHelper::splitToken($token);
		AuthHelper::setToken($tokenArray['token']);
		try {
            $loginEntity = TokenHelper::authByToken($tokenArray['token'], $tokenArray['type']);
		} catch(NotFoundHttpException $e) {
			throw new UnauthorizedHttpException();
		}
		if(empty($loginEntity)) {
			$this->breakSession();
		}
		$this->checkStatus($loginEntity);
		return $loginEntity;
	}
	
	public function logout() {
		Yii::$app->user->logout();
		AuthHelper::setToken('');
	}
	
	public function denyAccess() {
		if(Yii::$app->user->getIsGuest()) {
			$this->loginRequired();
            $this->breakSession();
		} else {
			throw new ForbiddenHttpException();
		}
	}
	
	public function loginRequired() {
		try {
			Yii::$app->user->loginRequired();
		} catch(InvalidConfigException $e) {
			return;
		}
	}
	
	public function breakSession() {
		if(APP == CONSOLE) {
			return;
		}
		if(APP == API) {
			throw new UnauthorizedHttpException;
		} else {
			$this->logout();
			Yii::$app->session->destroy();
			Yii::$app->response->cookies->removeAll();
			$this->loginRequired();
		}
	}
	
	public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id') {
		if($entity->{$fieldName} != \App::$domain->account->auth->identity->id) {
			throw new ForbiddenHttpException();
		}
	}
	
}
