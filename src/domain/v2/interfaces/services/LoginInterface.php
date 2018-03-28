<?php

namespace yii2module\account\domain\v2\interfaces\services;

interface LoginInterface {

	public function oneById($id);
	public function oneByLogin($login);
	public function create($data);
	public function isForbiddenByStatus($status);

}