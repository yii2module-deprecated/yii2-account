<?php

namespace yii2module\account\domain\v2\services;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\misc\enums\TimeEnum;
use yii2module\account\domain\v2\entities\ConfirmEntity;
use yii2module\account\domain\v2\exceptions\ConfirmAlreadyExistsException;
use yii2module\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2lab\domain\services\BaseService;
use Yii;
use yii2module\account\domain\v2\forms\RegistrationForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\interfaces\services\RegistrationInterface;

class RegistrationService extends BaseService implements RegistrationInterface {
	
	const CONFIRM_ACTION = 'registration';
	
	public $expire = TimeEnum::SECOND_PER_MINUTE * 20;
	
	//todo: изменить путь чтения временного аккаунта для ригистрации. Инкапсулировать все в ядро. Сейчас запрос идет на прямую.
	public function createTempAccount($login, $email = null) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'email']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_REQUEST);
		$this->checkLoginExistsInTps($login);
		try {
			Yii::$domain->account->confirm->send($login, self::CONFIRM_ACTION, $this->expire, ArrayHelper::toArray($body));
		} catch(ConfirmAlreadyExistsException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/confirm', 'already_sended_code {phone}', ['phone' => LoginHelper::format($login)]);
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	public function checkActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'activation_code']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function activateAccount($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		$this->checkActivationCode($login, $activation_code);
		Yii::$domain->account->confirm->activate($login, self::CONFIRM_ACTION, $activation_code);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$login = LoginHelper::pregMatchLogin($login);
		$body = compact(['login', 'activation_code', 'password']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CONFIRM);
		//$this->activateAccount($login, $activation_code);
		
		/** @var ConfirmEntity $confirmEntity */
		$confirmEntity = $this->verifyActivationCode($login, $activation_code);
		
		if(empty($email)) {
			$email = $confirmEntity->data['email'];
		}
		if(empty($email)) {
			$email = 'demo@wooppay.com';
		}
		
		$data = compact('login','password','email');
		Yii::$domain->account->login->create($data);
		Yii::$domain->account->confirm->delete($login, self::CONFIRM_ACTION);
	}

	private function checkLoginExistsInTps($login) {
		$login = LoginHelper::pregMatchLogin($login);
		/** @var LoginInterface $loginRepository */
		$loginRepository = $this->domain->repositories->login;
		$isExists = $loginRepository->isExistsByLogin($login);
		if($isExists) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	private function verifyActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		try {
			return Yii::$domain->account->confirm->verifyCode($login, self::CONFIRM_ACTION, $activation_code);
		} catch(ConfirmIncorrectCodeException $e) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/confirm', 'incorrect_code');
			throw new UnprocessableEntityHttpException($error);
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'temp_user_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
