<?php

namespace yii2module\account\domain\entities;

use yii2lab\domain\BaseEntity;

class LoginNativeEntity extends BaseEntity {

	protected $id;
	protected $login;
	protected $email;
	protected $subject_type = 3000;
	protected $token;
	protected $parent_id;
	protected $iin_fixed = false;
	protected $creation_date;
	protected $role;
	protected $password;
	
}
