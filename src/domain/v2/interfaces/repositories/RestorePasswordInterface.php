<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;

/**
 * Class RestorePasswordRepository
 *
 * @package yii2module\account\domain\v2\repositories\ar
 *
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
interface RestorePasswordInterface {
	
	public function requestNewPassword($login, $mail = null);
	
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @deprecated  use passwordChangeByAuthKey()
	 */
	public function checkActivationCode($login, $code, $password);
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @deprecated use passwordChangeByAuthKey()
	 */
	public function setNewPassword($login, $code, $password);

	public function passwordChangeByAuthKey($login, $code, $password);
}