<?php

namespace yii2module\account\domain\v2\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\TimeValue;

class TempEntity extends BaseEntity {

	protected $login;
	protected $email;
	protected $activation_code;
	protected $ip;
	protected $password;
	protected $created_at;
	
	public function init() {
		parent::init();
		$this->created_at = new TimeValue;
	}
	
}
