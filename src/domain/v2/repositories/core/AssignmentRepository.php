<?php

namespace yii2module\account\domain\v2\repositories\core;

use yii2lab\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2module\account\domain\v2\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\repositories\traits\AssignmentTrait;

class AssignmentRepository extends BaseActiveCoreRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}