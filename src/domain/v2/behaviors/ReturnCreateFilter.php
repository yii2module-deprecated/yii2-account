<?php

namespace yii2module\account\domain\v2\behaviors;

use yii\base\Behavior;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\MethodEvent;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\domain\services\base\BaseService;

class ReturnCreateFilter extends Behavior {
	
	public $methods;
	
	public function events() {
		return [
			EventEnum::EVENT_AFTER_METHOD => 'create',
		];
	}
	
	public function prepare(MethodEvent $event) {
		dd(1);
	}
	

}
