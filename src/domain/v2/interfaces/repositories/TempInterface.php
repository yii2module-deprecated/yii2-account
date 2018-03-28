<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;

interface TempInterface extends CrudInterface {
	
	public function oneByLogin($login);
	public function isExists($login);

}