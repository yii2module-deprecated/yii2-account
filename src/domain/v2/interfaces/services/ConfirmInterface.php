<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\interfaces\services\CrudInterface;

interface ConfirmInterface extends CrudInterface {
	
	public function delete($login, $action);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $code
	 * @param $smsCodeExpire
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	public function isVerifyCode($login, $action, $code, $smsCodeExpire);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $smsCodeExpire
	 *
	 * @return mixed
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLoginAndAction($login, $action, $smsCodeExpire);
	public function createNew($login, $action, $smsCodeExpire, $data = null);

}