<?php

namespace yii2module\account\domain\v2\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\forms\RestorePasswordForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\BaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\interfaces\services\RestorePasswordInterface;

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

	public function request($login, $mail = null) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->validateLogin($login);
		$this->repository->requestNewPassword($login, $mail);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
		$this->repository->setNewPassword($login, $activation_code, $password);
	}
	
	protected function validateLogin($login) {
		$user = $this->domain->login->isExistsByLogin($login);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/main', 'login_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code) {
		try {
			$isChecked = $this->repository->checkActivationCode($login, $activation_code);
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/restore-password', 'not_found_request');
			throw new UnprocessableEntityHttpException($error);
		}
		if(!$isChecked) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/restore-password', 'invalid_activation_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
