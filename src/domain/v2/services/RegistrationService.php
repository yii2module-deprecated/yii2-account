<?php

namespace yii2module\account\domain\v2\services;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\base\BaseService;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\entities\ConfirmEntity;
use yii2module\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yii2module\account\domain\v2\helpers\LoginHelper;
use Yii;
use yii2module\account\domain\v2\forms\RegistrationForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\interfaces\services\RegistrationInterface;
use yii2woop\common\components\RBACOperations;
use yii\web\ForbiddenHttpException;

class RegistrationService extends BaseService implements RegistrationInterface {
	
	const CONFIRM_ACTION = 'registration';
	
	public $expire = TimeEnum::SECOND_PER_MINUTE * 1;
	public $requiredEmail = false;
	
	private function validateLogin($login) {
		if(!\App::$domain->account->login->isValidLogin($login)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/login', 'not_valid');
			throw new UnprocessableEntityHttpException($error);
		}
		$login = \App::$domain->account->login->normalizeLogin($login);
		return $login;
	}

	public static function checkPrefix() {
		$partnerName = Yii::$app->request->getHeaders()->get('partner-name');
		if (empty($partnerName)) {
			return $prefix = null;
		}
		$arrPartnerName = explode(", ", $partnerName);
		if(count($arrPartnerName)>1){
			$partnerName = $arrPartnerName[count($arrPartnerName)-1];
		}
		try {
			$prefix = \App::$domain->partner->info->oneByNameRaw($partnerName)->prefix;
			return $prefix;
		} catch (\Exception $e) {
			return $prefix = null;
		}
	}

	//todo: изменить путь чтения временного аккаунта для ригистрации. Инкапсулировать все в ядро. Сейчас запрос идет на прямую.
	public function createTempAccount($login, $email = null) {
		$this->isHasPossibility();
		$body = compact(['login', 'email']);
		$scenario = RegistrationForm::SCENARIO_REQUEST;
		if($this->requiredEmail) {
			$scenario = RegistrationForm::SCENARIO_REQUEST_WITH_EMAIL;
		}
		Helper::validateForm(RegistrationForm::class, $body, $scenario);
		$login = $this->validateLogin($login);
		$this->checkLoginExistsInTps($this->checkPrefix() . $login);
		\App::$domain->account->confirm->send($login, self::CONFIRM_ACTION, $this->expire, ArrayHelper::toArray($body));
	}
	
	public function checkActivationCode($login, $activation_code) {
		$login = $this->validateLogin($login);
		$body = compact(['login', 'activation_code']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function activateAccount($login, $activation_code) {
		$login = $this->validateLogin($login);
		$this->checkActivationCode($login, $activation_code);
		\App::$domain->account->confirm->activate($login, self::CONFIRM_ACTION, $activation_code);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$login = $this->validateLogin($login);
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
		\App::$domain->account->login->create($data);
		\App::$domain->account->confirm->delete($login, self::CONFIRM_ACTION);
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
			return \App::$domain->account->confirm->verifyCode($login, self::CONFIRM_ACTION, $activation_code);
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

	private function isHasPossibility() {
		if(!Yii::$app->user->isGuest){
			if(!Yii::$app->user->can(RBACOperations::CREATE_UNKNOWN_USER)){
				throw new ForbiddenHttpException();
			}
		}
	}
}
