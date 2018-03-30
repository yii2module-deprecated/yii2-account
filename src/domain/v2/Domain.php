<?php

namespace yii2module\account\domain\v2;

use common\enums\rbac\RoleEnum;
use yii2lab\domain\enums\Driver;
use yii2lab\misc\enums\TimeEnum;

// todo: описание докблоков в руководство

/**
 * Class Domain
 *
 * @package yii2module\account\domain\v2
 *
 * @property \yii2module\account\domain\v2\interfaces\services\AuthInterface $auth
 * @property \yii2module\account\domain\v2\interfaces\services\LoginInterface $login
 * @property \yii2module\account\domain\v2\interfaces\services\RegistrationInterface $registration
 * @property \yii2module\account\domain\v2\interfaces\services\TempInterface $temp
 * @property \yii2module\account\domain\v2\interfaces\services\RestorePasswordInterface $restorePassword
 * @property \yii2module\account\domain\v2\interfaces\services\SecurityInterface $security
 * @property \yii2module\account\domain\v2\interfaces\services\TestInterface $test
 * @property \yii2module\account\domain\v2\interfaces\services\RbacInterface $rbac
 * @property \yii2module\account\domain\v2\interfaces\services\ConfirmInterface $confirm
 * @property \yii2module\account\domain\v2\interfaces\services\AssignmentInterface $assignment
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		$remoteServiceDriver = Driver::primary() == Driver::CORE ? Driver::CORE : null;
		$serviceNamespace = Driver::primary() == Driver::CORE ? 'yii2module\account\domain\v2\services\core' : 'yii2module\account\domain\v2\services';
		return [
			'repositories' => [
				'auth' => Driver::primary(),
				'login' => Driver::primary(),
				'temp' => Driver::ACTIVE_RECORD,
				'restorePassword' => Driver::primary(),
				'security' => Driver::primary(),
				'test' => Driver::FILEDB,
				'rbac' => Driver::MEMORY,
				'confirm' => Driver::ACTIVE_RECORD,
				'assignment' => Driver::primary(),
			],
			'services' => [
				'auth' => [
					'rememberExpire' => TimeEnum::SECOND_PER_YEAR,
				],
				'login' => [
					'prefixList' => ['B', 'BS', 'R', 'QRS'],
					'defaultRole' => RoleEnum::UNKNOWN_USER,
					'defaultStatus' => 1,
					'forbiddenStatusList' => [0],
				],
				'registration' => $remoteServiceDriver, //$serviceNamespace . '\RegistrationService',
				'temp',
				'restorePassword' => $serviceNamespace . '\RestorePasswordService',
				'security',
				'test',
				'rbac',
				'confirm',
				'assignment',
			],
		];
	}
	
}