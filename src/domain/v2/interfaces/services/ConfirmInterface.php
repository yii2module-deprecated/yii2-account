<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

interface ConfirmInterface extends CrudInterface {
	
	public function delete($login, $action);
	public function isVerifyCode($login, $action, $code, $smsCodeExpire);
	public function oneByLoginAndAction($login, $action, $smsCodeExpire);
	public function createNew($login, $action, $smsCodeExpire, $data = null);

}