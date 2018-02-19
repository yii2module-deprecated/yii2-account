<?php

namespace yii2module\account\domain\v1\repositories\core;

use common\enums\app\ApiVersionEnum;
use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\repositories\CoreRepository;
use yii2lab\helpers\Registry;
use yii2module\account\domain\v1\entities\LoginEntity;
use yii2module\account\domain\v1\helpers\LoginEntityFactory;
use yii2module\account\domain\v1\interfaces\repositories\AuthInterface;
use yii2woop\generated\transport\TpsCommands;

class AuthRepository extends CoreRepository implements AuthInterface {
	
	public $baseUri = 'auth';
	public $version = ApiVersionEnum::VERSION_4;
	
	public function authentication($login, $password) {
		$response = $this->post(null, compact('login', 'password'));
		return $this->forgeEntity($response->data, LoginEntity::className());
	}
	
	public function pseudoAuthenticationWithParrent($login, $ip, $email = null, $parentLogin) {
		TpsCommands::
		$response = $this->post(null, compact('login','ip','email', 'parentLogin'));
		return $this->forgeEntity($response->data);
	}

	public function setToken($token) {
		if (Yii::$app->user->enableSession) {
			Yii::$app->session['token'] = $token;
		}
		Registry::set('authToken', $token);
	}
	
	protected function forgeLoginEntity($user, $class = null)
	{
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		$user = $this->alias->decode($user);
		return LoginEntityFactory::forgeLoginEntity($user);
	}
}