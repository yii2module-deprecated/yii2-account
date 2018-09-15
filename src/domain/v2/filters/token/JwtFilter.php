<?php

namespace yii2module\account\domain\v2\filters\token;

use yii\helpers\ArrayHelper;
use yii2lab\extension\scenario\base\BaseScenario;

class JwtFilter extends BaseScenario {

    public $token;

	public function run() {
        $tokenEntity = \Dii::$domain->jwt->token->decode($this->token);
        $loginEntity = \Dii::$domain->account->login->oneById($tokenEntity->subject['id']);
        $this->setData($loginEntity);
	}

}
