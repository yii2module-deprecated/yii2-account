<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;

interface ConfirmInterface extends CrudInterface {
	
	public function oneByLoginAndAction($login, $action);
	public function oneByLogin($login);
	public function cleanOld($login, $action, $expire = 30);

}