<?php

namespace yii2module\account\domain\v2\filters\token;

use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii2module\account\domain\v2\entities\LoginEntity;

abstract class BaseTokenFilter extends BaseObject {

	public $type;
	
	/**
	 * @param $token
	 *
	 * @return LoginEntity
	 */
	abstract public function auth($token);
	
	/**
	 * @param $body
	 * @param $ip
	 *
	 * @return LoginEntity
	 */
	abstract public function login($body, $ip);

	protected function forgeToken($token) {
		if(empty($this->type)) {
			throw new InvalidArgumentException('Attribute "type" not defined in filter "' . static::class . '"!');
		}
		return $token = $this->type . SPC . $token;
	}
}
