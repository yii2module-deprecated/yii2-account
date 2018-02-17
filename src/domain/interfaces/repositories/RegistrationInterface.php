<?php

namespace yii2module\account\domain\interfaces\repositories;

interface RegistrationInterface {
	
	public function generateActivationCode();
	public function create($data);
	public function isExists($login);

}