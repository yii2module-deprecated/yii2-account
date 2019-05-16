<?php

namespace yii2module\account\domain\v2\repositories\ar;

use Yii;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\interfaces\repositories\RestorePasswordInterface;

class RestorePasswordRepository extends BaseRepository implements RestorePasswordInterface {

	const CONFIRM_ACTION = 'restore-password';
	
	public $smsCodeExpire = TimeEnum::SECOND_PER_HOUR;
	
	public function requestNewPassword($login, $mail = null) {
		$login = LoginHelper::getPhone($login);
		$entity = $this->domain->confirm->createNew($login, self::CONFIRM_ACTION, $this->smsCodeExpire);
		$message = Yii::t('account/restore-password', 'restore_password_sms {activation_code}', ['activation_code' => $entity->activation_code]);
		\App::$domain->notify->sms->send($login, $message);
	}
	
	public function checkActivationCode($login, $code) {
		// TODO: Implement checkActivationCode() method.
	}
	
	public function setNewPassword($login, $code, $password) {
		// TODO: Implement setNewPassword() method.
	}
	
}