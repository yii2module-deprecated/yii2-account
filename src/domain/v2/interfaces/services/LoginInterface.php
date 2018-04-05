<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

interface LoginInterface extends CrudInterface {

	public function oneByLogin($login);
	public function isForbiddenByStatus($status);

}