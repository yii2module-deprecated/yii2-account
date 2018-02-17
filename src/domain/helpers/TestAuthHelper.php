<?php

namespace yii2module\account\domain\helpers;

use Yii;
use yii2lab\domain\Domain;
use yii2lab\domain\enums\Driver;
use yii2lab\domain\helpers\ConfigHelper;
use yii2module\account\domain\entities\LoginEntity;

class TestAuthHelper {
	
	const ADMIN_PASSWORD = 'Wwwqqq111';
	
	public static function authByLogin($login, $password = self::ADMIN_PASSWORD) {
		self::defineAccountDomain();
		$userEntity = Yii::$app->account->auth->authentication($login, $password);
		Yii::$app->user->setIdentity($userEntity);
	}
	
	public static function authById($id) {
		self::defineAccountDomain();
		$userEntity = Yii::$app->account->login->oneById($id);
		Yii::$app->user->setIdentity($userEntity);
	}
	public static function getAccountDomainDefinition() {
		$domainDefinition = [
			'class' => Domain::class,
			'path' => 'yii2module\account\domain',
			'repositories' => [
				'auth' => Driver::DISC,
				'login' => [
					'driver' => Driver::DISC,
					'path' => '@yii2module/account/domain/fixtures/data',
				],
				'rbac' => Driver::MEMORY,
				'assignment' => Driver::DISC,
			],
			'services' => [
				'auth',
				'login' => [
					'relations' => [],
					'prefixList' => ['B', 'BS', 'R', 'QRS'],
					'defaultRole' => null,
					'defaultStatus' => 1,
					'forbiddenStatusList' => [0],
				],
				'rbac',
				'assignment',
			],
		];
		return ConfigHelper::normalizeItemConfig('account', $domainDefinition);
	}
	
	/**
	 * @throws \yii\base\InvalidConfigException
	 */
	public static function defineAccountDomain() {
		if(!Yii::$app->has('account')) {
			$domainDefinition = self::getAccountDomainDefinition();
			Yii::$app->set('account', $domainDefinition);
		}
	}
	
}
