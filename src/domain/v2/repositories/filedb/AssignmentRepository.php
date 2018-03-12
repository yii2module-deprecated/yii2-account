<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii2module\account\domain\v2\interfaces\repositories\AssignmentInterface;
use yii2module\account\domain\v2\repositories\traits\AssignmentTrait;

class AssignmentRepository extends ActiveFiledbRepository implements AssignmentInterface {
	
	use AssignmentTrait;
	
	protected $primaryKey = null;
	
}