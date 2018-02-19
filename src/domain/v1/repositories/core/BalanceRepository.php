<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii2lab\domain\repositories\ActiveCoreRepository;
use common\enums\app\ApiVersionEnum;

class BalanceRepository extends ActiveCoreRepository {
	
	public $baseUri = 'balance';
	public $version = ApiVersionEnum::VERSION_4;
	
}