<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

interface TempInterface extends CrudInterface {
	
	public function isActivated($login);
	public function checkActivationCode($login, $code);
	public function activate($login);
	public function delete($login);
	
}