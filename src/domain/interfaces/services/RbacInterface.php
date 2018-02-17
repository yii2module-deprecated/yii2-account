<?php

namespace yii2module\account\domain\interfaces\services;

interface RbacInterface {

	public function can($rule, $param = null, $allowCaching = true);

}