<?php

namespace yii2module\account\domain\v2\repositories\test;

use yii2module\account\domain\v2\interfaces\repositories\RestorePasswordInterface;
use yii2lab\domain\repositories\BaseRepository;

/**
 * Class RestorePasswordRepository
 *
 * @package yii2module\account\domain\v2\repositories\test
 *
 * @deprecated
 */
class RestorePasswordRepository extends BaseRepository implements RestorePasswordInterface {

	public function requestNewPassword($login, $mail = null) {
	
	}
	
	public function checkActivationCode($login, $code, $password) {
		if($code == 111111) {
			return true;
		}
		return false;
	}
	
	public function setNewPassword($login, $code, $password) {
	
	}
	
	public function passwordChangeByAuthKey($login, $code, $password) {
		// TODO: Implement passwordChangeByAuthKey() method.
	}
}