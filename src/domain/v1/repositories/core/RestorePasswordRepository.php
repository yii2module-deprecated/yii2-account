<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii2module\account\domain\v1\interfaces\repositories\coreorePasswordInterface;
use yii2lab\domain\repositories\CoreRepository;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class RestorePasswordRepository extends CoreRepository implements RestorePasswordInterface {
	
	public $version = 'v4';
	public $baseUri = 'auth/restore-password';

	/**
	 * @param $login
	 * @param null $mail
	 */
	public function requestNewPassword($login, $mail = null) {
		$this->post('request', compact('login'));
	}

	/**
	 * @param $login
	 * @param $activation_code
	 * @return bool
	 */
	public function checkActivationCode($login, $activation_code) {
		try {
			$this->post('check-code', compact('login', 'activation_code'));
			return true;
		} catch(UnprocessableEntityHttpException $e) {
			return false;
		}
	}

	/**
	 * @param $login
	 * @param $activation_code
	 * @param $password
	 */
	public function setNewPassword($login, $activation_code, $password) {
		$this->post('confirm', compact('login', 'activation_code', 'password'));
	}
	
	public function isExists($login) {
		return $this->domain->repositories->login->isExistsByLogin($login);
	}
}