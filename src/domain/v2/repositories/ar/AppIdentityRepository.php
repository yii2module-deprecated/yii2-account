<?php

namespace yii2module\account\domain\v2\repositories\ar;



use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\AppIdentityInterface;

class AppIdentityRepository extends BaseActiveArRepository implements  AppIdentityInterface {

	public $primaryKey = 'login';

	public function tableName() {
		return 'app_identity';
	}

}