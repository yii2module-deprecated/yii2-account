<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2module\account\domain\v2\interfaces\repositories\RestorePasswordInterface;
use yii2module\account\domain\v2\repositories\base\BaseAuthRepository;

class RestorePasswordRepository extends BaseAuthRepository implements RestorePasswordInterface {
	public function requestNewPassword($login, $mail = null) {
		// TODO: Implement requestNewPassword() method.
	}
	
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @deprecated  use passwordChangeByAuthKey()
	 */
	public function checkActivationCode($login, $code, $password) {
		// TODO: Implement checkActivationCode() method.
	}
	
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @deprecated use passwordChangeByAuthKey()
	 */
	public function setNewPassword($login, $code, $password) {
		// TODO: Implement setNewPassword() method.
	}
	
	public function passwordChangeByAuthKey($login, $code, $password) {
		// TODO: Implement passwordChangeByAuthKey() method.
	}

	public function resendCode($login, $email, $url)
	{
		// TODO: Implement resendCode() method.
	}
}