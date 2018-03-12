<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\repositories\traits\AssignmentTrait;

class AssignmentRepository extends ActiveArRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}