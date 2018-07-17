<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\TempInterface;

class TempRepository extends ActiveArRepository implements TempInterface {
	
	protected $primaryKey = null;
	
	public function tableName() {
		return 'user_registration';
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
}