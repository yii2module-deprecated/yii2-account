<?php

namespace yii2module\account\domain\repositories\tps;

use Exception;
use Yii;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\repositories\TpsRepository;
use yii2lab\helpers\Registry;
use yii2module\account\domain\helpers\LoginEntityFactory;
use yii2module\account\domain\interfaces\repositories\AuthInterface;
use yii2woop\common\components\TpsTransport;
use yii2woop\generated\exception\tps\TpsUnknownException;
use yii2woop\generated\exception\TpsException;
use yii2woop\generated\transport\TpsCommands;

class AuthRepository extends TpsRepository implements AuthInterface {
	
	public function fieldAlias() {
		return [
			'id' => 'subject_id',
			'token' => 'authToken',
		];
	}
	
	public function authentication($login, $password) {
		try {
			$address = TpsTransport::getUserHostAddress();
			$request = TpsCommands::authentication($login, $password, $address);
			$user = $request->send();
			if($user) {
				return $this->forgeLoginEntity($user);
			}
		} catch(Exception $e) {
		}
		return false;
	}
	
	public function pseudoAuthenticationWithParent($login, $ip, $subjectType, $email = null, $parentLogin = null) {
		if($parentLogin) {
			try {
				$userLogin = Yii::$app->account->partner->getPrefixByPartnerLogin($parentLogin);
			} catch(NotFoundHttpException $e) {
				throw new NotFoundHttpException('partner_not_found');
			}
		}
		$prefix = !empty($userLogin) ? $userLogin->getPrefix() : '';
		try {
			$user = TpsCommands::pseudoAuthentication($prefix . $login, $ip, $subjectType, $email, $parentLogin)->send();
			if($user) {
				return $this->forgeLoginEntity($user);
			} else {
				Throw new NotFoundHttpException('user_not_found');
			}
		} catch(TpsException $e) {
			throw new TpsUnknownException($e);
		} catch(Exception $e) {
			throw new UnknownPropertyException($e);
		}
		return false;
	}
	
	public function pseudoAuthentication($login, $ip, $subjectType, $email = null) {
		try {
			
			$request = TpsCommands::pseudoAuthentication($login, $ip, $subjectType, $email);
			$user = $request->send();
			
			if($user) {
				return $this->forgeLoginEntity($user);
			}
		} catch(Exception $e) {
			return false;
		}
		return false;
	}
	
	public function setToken($token) {
		if(Yii::$app->user->enableSession) {
			Yii::$app->session['token'] = $token;
		}
		Registry::set('authToken', $token);
	}
	
	protected function forgeLoginEntity($user, $class = null) {
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		$user = $this->alias->decode($user);
		return LoginEntityFactory::forgeLoginEntity($user);
	}
}