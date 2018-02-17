<?php

use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\enums\Driver;
use yii2lab\test\helpers\TestHelper;

$config = [
	'account' => [
		'class' => Domain::class,
		'path' => 'yii2module\account\domain',
		'repositories' => [
			'auth' => Driver::primary(),
			'login' => [
				'driver' => Driver::primary(),
				'path' => '@yii2module/account/domain/fixtures/data',
			],
			'registration' => Driver::primary(),
			'temp' => Driver::ACTIVE_RECORD,
			'restorePassword' => Driver::primary(),
			'security' => Driver::primary(),
			'test' => Driver::DISC,
			'balance' => Driver::primary(),
			'rbac' => Driver::MEMORY,
			'confirm' => Driver::ACTIVE_RECORD,
			'assignment' => Driver::primary(),
		],
		'services' => [
			'auth',
			'login' => [
				'relations' => [
					/*'profile' => 'profile.profile',
					'address' => 'profile.address',*/
				],
				'prefixList' => ['B', 'BS', 'R', 'QRS'],
				'defaultRole' => null,
				'defaultStatus' => 1,
				'forbiddenStatusList' => [0],
			],
			'registration' => $remoteServiceDriver,
			'temp',
			'restorePassword' => $remoteServiceDriver,
			'security',
			'test',
			'balance',
			'rbac',
			'confirm',
			'assignment',
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
