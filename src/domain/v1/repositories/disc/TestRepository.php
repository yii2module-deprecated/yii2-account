<?php

namespace yii2module\account\domain\v1\repositories\disc;

use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveDiscRepository;

class TestRepository extends ActiveDiscRepository {
	
	public $table = 'user';

	public function getOneByRole($role) {
		$query = Query::forge();
		$query->where('role', $role);
		return $this->one($query);
	}

	public function oneByLogin($login) {
		$query = Query::forge();
		$query->where('login', $login);
		return $this->one($query);
	}

}