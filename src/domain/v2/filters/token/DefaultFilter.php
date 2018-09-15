<?php

namespace yii2module\account\domain\v2\filters\token;

use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;
use yii2lab\extension\scenario\base\BaseScenario;

class DefaultFilter extends BaseScenario {

    public $token;

	public function run() {
	    $loginEntity = \Dii::$domain->account->repositories->login->oneByToken($this->token);
	    $this->setData($loginEntity);
	}

}
