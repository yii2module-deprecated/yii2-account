<?php

use yii\helpers\ArrayHelper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2lab\test\helpers\TestHelper;

$config = [
	'lang' => 'yii2module\lang\domain\Domain',
	'rbac' => 'yii2lab\rbac\domain\Domain',
	'geo' => 'yii2lab\geo\domain\Domain',
	'partner' => 'yii2woop\partner\domain\Domain',
	'account' => \yii2woop\common\domain\account\v2\helpers\DomainHelper::config([
		'services' => [
			'registration' => [
				'expire' => TimeEnum::SECOND_PER_MINUTE * 3,
			],
			'activity' => [
				'sources' => [
					'account.auth',
					'account.registration',
					'operation.custom',
					'operation.payment',
					'operation.transfer',
					'operation.balance',
				],
			]
		],
	]),
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
