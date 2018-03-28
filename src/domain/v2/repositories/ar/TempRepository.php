<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;

class TempRepository extends ActiveArRepository {
	
	protected $primaryKey = 'login';
	
	public function tableName() {
		return 'user_registration';
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
}