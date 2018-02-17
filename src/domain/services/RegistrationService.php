<?php

namespace yii2module\account\domain\services;

use yii2module\account\domain\helpers\LoginHelper;
use yii2lab\domain\services\BaseService;
use Yii;
use yii2module\account\domain\forms\RegistrationForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class RegistrationService extends BaseService {
	
	//todo: изменить путь чтения временного аккаунта для ригистрации. Инкапсулировать все в ядро. Сейчас запрос идет на прямую.
	public function createTempAccount($login, $email = null) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'email']);
		
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_REQUEST);
	
		$this->checkLoginExistsInTps($login);
	
		$activation_code = $this->repository->generateActivationCode();

		Yii::$app->account->temp->create(compact('login', 'email', 'activation_code'));
	
		$this->sendSmsWithActivationCode($login, $activation_code);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'activation_code']);
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_CHECK);
		$this->checkLoginExistsInTemp($login);
		//$this->isActivated($login);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function activateAccount($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		$this->checkActivationCode($login, $activation_code);
		Yii::$app->account->temp->activate($login);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'activation_code', 'password']);
		$this->validateForm(RegistrationForm::className(), $body, RegistrationForm::SCENARIO_CONFIRM);
		$this->checkLoginExistsInTemp($login);
		if(empty($email)) {
			$email = 'demo@wooppay.com';
		}
		$this->verifyActivationCode($login, $activation_code);
		$data = compact('login','password','email');
		$this->repository->create($data);
		Yii::$app->account->temp->delete($login);
	}

	protected function checkLoginExistsInTemp($login) {
		$login = LoginHelper::pregMatchLogin($login);
		$isExists = $this->domain->repositories->temp->isExists($login);
	
		if(!$isExists) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'temp_user_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	
	}

	protected function sendSmsWithActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		$loginParts = LoginHelper::splitLogin($login);
		$message = Yii::t('account/registration', 'activate_account_sms {activation_code}', ['activation_code' => $activation_code]);
		Yii::$app->notify->sms->send($loginParts['phone'], $message);
	}

	protected function checkLoginExistsInTps($login) {
		$login = LoginHelper::pregMatchLogin($login);
		$isExists = $this->repository->isExists($login);
	
		if($isExists) {
			$error = new ErrorCollection();
		
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
	
			throw new UnprocessableEntityHttpException($error);
		}
	
	}
	
	protected function isActivated($login) {
		$login = LoginHelper::pregMatchLogin($login);
		if(Yii::$app->account->temp->isActivated($login)) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/registration', 'already_activated');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		if(!Yii::$app->account->temp->checkActivationCode($login, $activation_code)) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/registration', 'invalid_activation_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
