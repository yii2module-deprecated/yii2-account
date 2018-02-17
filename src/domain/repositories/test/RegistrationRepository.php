<?php

namespace yii2module\account\domain\repositories\test;

use yii2module\account\domain\interfaces\repositories\RegistrationInterface;
use yii2lab\domain\repositories\BaseRepository;

class RegistrationRepository extends BaseRepository implements RegistrationInterface {

	public function generateActivationCode() {
		return 123456;
	}
	
	public function create($data) {
	
	}
	
	public function isExists($login) {
		return $this->domain->repositories->test->isExists(['login' => $login]);
	}
	
}