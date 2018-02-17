<?php

namespace yii2module\account\domain\services;

use yii2module\account\domain\forms\RestorePasswordForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\BaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;

class RestorePasswordService extends BaseService {
	
	public function request($login, $mail = null) {
		$body = compact(['login']);
		$this->validateForm(RestorePasswordForm::className(), $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->validateLogin($login);
		$this->repository->requestNewPassword($login, $mail);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		$this->validateForm(RestorePasswordForm::className(), $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		$this->validateForm(RestorePasswordForm::className(), $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
		$this->repository->setNewPassword($login, $activation_code, $password);
	}
	
	protected function validateLogin($login) {
		$user = $this->repository->isExists($login);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/main', 'login_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code) {
		$isChecked = $this->repository->checkActivationCode($login, $activation_code);
		if(!$isChecked) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/password', 'invalid_activation_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
