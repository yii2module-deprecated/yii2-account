<?php

namespace yii2module\account\domain\v1\repositories\tps;

use Yii;
use yii2lab\domain\repositories\TpsRepository;
use yii2woop\generated\exception\tps\NotAuthenticatedException;
use yii2woop\generated\transport\TpsCommands;

class BalanceRepository extends TpsRepository {
	
	public function all() {
		$login = Yii::$app->user->identity->login;
		$request = TpsCommands::getSubjectBalance($login);
		try {
			$response = $request->send();
		} catch(NotAuthenticatedException $e) {
			$response = ['active' => 0];
		}
		return $this->forgeEntity($response);
	}
}