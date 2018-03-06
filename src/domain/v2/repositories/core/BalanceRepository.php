<?php

namespace yii2module\account\domain\v2\repositories\core;

use yii2lab\domain\repositories\ActiveCoreRepository;
use common\enums\app\ApiVersionEnum;

class BalanceRepository extends ActiveCoreRepository {
	
	public $baseUri = 'balance';
	public $version = 'v4';
	
}