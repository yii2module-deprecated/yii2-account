<?php

namespace yii2module\account\domain\v2\validators;

use yii2lab\extension\validator\BaseValidator;

class LoginValidator extends BaseValidator {
	
	protected $messageLang = ['account/login', 'not_valid'];
	
	protected function validateValue($value) {
		$isValid = \App::$domain->account->login->isValidLogin($value);
		return $this->prepareMessage($isValid);
	}
	
}
