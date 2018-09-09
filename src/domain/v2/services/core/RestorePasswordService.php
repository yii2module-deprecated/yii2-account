<?php

namespace yii2module\account\domain\v2\services\core;

use yii2lab\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v2\forms\RestorePasswordForm;
use yii2lab\domain\services\CoreBaseService;
use yii2module\account\domain\v2\interfaces\services\RestorePasswordInterface;

/**
 * Class RestorePasswordService
 *
 * @package yii2module\account\domain\v2\services\core
 *
 * @property-read BaseCoreRepository $repository
 */
class RestorePasswordService extends CoreBaseService implements RestorePasswordInterface {
	
	public $point = 'auth/restore-password';
	public $tokenExpire;
	
	public function request($login, $mail = null) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->repository->post('request', $body);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->repository->post('check-code', $body);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->repository->post('confirm', $body);
	}
	
}
