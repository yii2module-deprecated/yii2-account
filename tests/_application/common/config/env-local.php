<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;

$config = [
	'servers' => [
		'db' => [
			'test' => [
				'driver' => null,
				'dsn' => 'sqlite:@common/runtime/sqlite/test-package.db',
			],
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/env-local.php');
return ArrayHelper::merge($baseConfig, $config);
