<?php

namespace yii2module\account\domain\v2\services;

use App;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\forms\RestorePasswordForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\BaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\interfaces\services\RestorePasswordInterface;
use yii2woop\generated\exception\tps\CallCounterExceededException;
use yii2woop\generated\exception\tps\InvalidPackageStructureException;
use yii2woop\generated\exception\tps\PasswordResetHashExpiredException;
use yii2woop\generated\exception\tps\WrongConfirmationCodeException;
use yii2woop\generated\transport\TpsCommands;
use yii2woop\partner\domain\entities\InfoEntity;

/**
 * Class RestorePasswordService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\RestorePasswordInterface $repository
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class RestorePasswordService extends BaseService implements RestorePasswordInterface {

	public $tokenExpire = TimeEnum::SECOND_PER_MINUTE * 1;

	/**
	 * @param $login
	 * @param null $mail
	 */
	public function request($login, $mail = null) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->validateLogin($login);
		$this->repository->requestNewPassword($login, $mail);
	}

	/**
	 * @param $login
	 * @param $activation_code
	 * @param $password
	 *
	 * @return mixed
	 */
	public function checkActivationCode($login, $activation_code, $password) {
		$this->validateLogin($login);
		$this->validateData($activation_code, $password);
		try {
			$request = TpsCommands::passwordChangeByAuthKey($login, $password, $activation_code);
			return $request->send();
		} catch (WrongConfirmationCodeException $e) {
			$error = new ErrorCollection();
			$error->add('smsCode', 'account/restore-password', 'invalid_sms');
			throw new UnprocessableEntityHttpException($error);
		} catch (CallCounterExceededException $e) {
			$error = new ErrorCollection();
			$error->add('smsCode', 'account/restore-password', 'too_many_attempts');
			throw new UnprocessableEntityHttpException($error);
		} catch(InvalidPackageStructureException $e) {
			$error = new ErrorCollection();
			$error->add('password', 'account/restore-password', 'enter_new_password');
			throw new UnprocessableEntityHttpException($error);
		}
	}

	/**
	 * @param $login
	 * @param $activation_code
	 */
	public function confirm($login, $activation_code) {
		$this->validateLogin($login);
		try {
			/** @var InfoEntity $partnerInfo */
			$partnerInfo = App::$domain->partner->info->oneFromHeader();
			$request = TpsCommands::sendConfirmationCodeByEmail($activation_code, $login, $partnerInfo->contacts->smsSenderName, $partnerInfo->contacts->smsProtocolType);
			return $request->send();
		} catch(PasswordResetHashExpiredException $e) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/restore-password', 'invalid_code');
			throw new UnprocessableEntityHttpException($error);
		} catch(InvalidPackageStructureException $e) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/restore-password', 'must_fill_field');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function validateLogin($login) {
		$user = $this->domain->login->isExistsByLogin($login);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/main', 'login_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}

	protected function validateData($activation_code, $password)	{
		if (empty($password)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/restore-password', 'enter_new_password');
			throw new UnprocessableEntityHttpException($error);}

		if (empty($activation_code)) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/restore-password', 'must_fill_field');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code, $password) {
		try {
			$this->repository->checkActivationCode($login, $activation_code, $password);
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/restore-password', 'not_found_request');
			throw new UnprocessableEntityHttpException($error);
		}
	}
}
