<?php

namespace yii2module\account\domain\v2\filters\token;

use yii\base\InvalidArgumentException;
use yii2lab\extension\scenario\base\BaseScenario;

abstract class BaseTokenFilter extends BaseScenario {

    public $token;
	public $type;
	
	abstract public function auth($body, $ip);

	protected function forgeToken($token) {
		if(empty($this->type)) {
			throw new InvalidArgumentException('Attribute "type" not defined in filter "' . static::class . '"!');
		}
		return $token = $this->type . SPC . $token;
	}
}
