<?php

namespace yii2module\account\domain\v2\services\core;

use yii2lab\core\domain\repositories\base\BaseCoreRepository;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\CoreBaseService;
use yii2module\account\domain\v2\forms\RegistrationForm;
use yii2module\account\domain\v2\interfaces\services\RegistrationInterface;

/**
 * Class RegistrationService
 *
 * @package yii2module\account\domain\v2\services\core
 *
 * @property-read BaseCoreRepository $repository
 */
class RegistrationService extends CoreBaseService implements RegistrationInterface {
	
	public $point = 'registration';
	
	public function createTempAccount($login, $email = null) {
		$body = compact('login', 'email');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_REQUEST);
		$this->repository->post('create-account', $body);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->repository->post('activate-account', $body);
	}
	
	public function activateAccount($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->repository->post('activate-account', $body);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$body = compact('login', 'activation_code', 'password');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CONFIRM);
		$this->repository->post('set-password', $body);
	}

}
