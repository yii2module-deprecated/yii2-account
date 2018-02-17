<?php

namespace yii2module\account\domain\services\core;

use yii2lab\domain\services\CoreBaseService;
use common\enums\app\ApiVersionEnum;
use yii2module\account\domain\forms\RegistrationForm;

class RegistrationService extends CoreBaseService {
	
	public $version = ApiVersionEnum::VERSION_4;
	public $baseUri = 'registration';
	
	public function createTempAccount($login, $email = null) {
		$body = compact('login', 'email');
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_REQUEST);
		$this->client->post('create-account', $body);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact('login', 'activation_code');
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_CHECK);
		$this->client->post('activate-account', $body);
	}
	
	public function activateAccount($login, $activation_code) {
		$body = compact('login', 'activation_code');
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_CHECK);
		$this->client->post('activate-account', $body);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$body = compact('login', 'activation_code', 'password');
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_CONFIRM);
		$this->client->post('set-password', $body);
	}

}
