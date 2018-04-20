<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

interface RestorePasswordInterface {
	
	public function requestNewPassword($login, $mail = null);
	public function checkActivationCode($login, $code);
	public function setNewPassword($login, $code, $password);

}