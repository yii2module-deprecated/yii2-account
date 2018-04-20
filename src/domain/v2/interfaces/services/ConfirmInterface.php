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
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	public function isVerifyCode($login, $action, $code);
	
	/**
	 * @param $login
	 * @param $action
	 *
	 * @return mixed
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLoginAndAction($login, $action);
	public function createNew($login, $action, $expire, $data = null);

}