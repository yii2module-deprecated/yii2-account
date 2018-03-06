<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

interface RegistrationInterface {
	
	public function generateActivationCode();
	public function create($data);
	public function isExists($login);

}