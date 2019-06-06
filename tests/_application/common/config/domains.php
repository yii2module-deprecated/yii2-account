<?php

use yii\helpers\ArrayHelper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2lab\test\helpers\TestHelper;
use yii2module\account\domain\v2\filters\login\LoginPhoneValidator;

$config = [
	'lang' => 'yii2module\lang\domain\Domain',
	'rbac' => 'yii2lab\rbac\domain\Domain',
	'geo' => \yii2woop\common\domain\geo\helpers\DomainHelper::config(),
	'account' => \yii2woop\common\domain\account\v2\helpers\DomainHelper::config([
		'services' => [
			'login' => [
				'loginValidator' => LoginPhoneValidator::class,
			],
			'registration' => [
				'expire' => TimeEnum::SECOND_PER_MINUTE * 3,
			],
		],
	]),
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
