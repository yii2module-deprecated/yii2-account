<?php

namespace yii2module\account\domain\v1\repositories\ar;

use Yii;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v1\helpers\LoginHelper;
use yii2module\account\domain\v1\interfaces\repositories\RestorePasswordInterface;
use yii2lab\domain\repositories\TpsRepository;

class RestorePasswordRepository extends TpsRepository implements RestorePasswordInterface {

	public $smsCodeExpire = TimeEnum::SECOND_PER_HOUR;

	/**
	 * @param $login
	 * @param null $mail
	 */
	public function requestNewPassword($login, $mail = null) {
		$login = LoginHelper::getPhone($login);
		$entity = $this->domain->confirm->createNew($login, 'restore-password', $this->smsCodeExpire);
		$message = Yii::t('account/registration', 'activate_account_sms {activation_code}', ['activation_code' => $entity->activation_code]);
		\App::$domain->notify->sms->send($login, $message);
	}

	/**
	 * @param $login
	 * @param $code
	 * @return mixed
	 */
	public function checkActivationCode($login, $code) {
		return $this->domain->confirm->isVerifyCode($login, 'restore-password', $code);
	}

	/**
	 * @param $login
	 * @param $code
	 * @param $password
	 */
	public function setNewPassword($login, $code, $password) {
		$model = $this->domain->repositories->login->getModel();
		$query = $model->find();
		$query->where(['login' => $login]);
		$user = $query->one();
		$user->password_hash = Yii::$app->security->generatePasswordHash($password);
		$user->save();
	}
	
	public function isExists($login) {
		return $this->domain->repositories->login->isExistsByLogin($login);
	}
}