<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\ActiveArRepository;

class PartnerRepository extends ActiveArRepository{

	protected $modelClass = 'yii2module\account\domain\v2\models\PartnerPrefixes';

	public function getPrefixByPartnerLogin($partnerLogin) {
		$query = Query::forge();
		$query->where('partner_login', $partnerLogin);
		return $this->one($query);
	}

}