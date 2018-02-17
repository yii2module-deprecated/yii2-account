<?php

namespace yii2module\account\domain\entities;

use yii2lab\domain\BaseEntity;

class PartnerEntity extends BaseEntity {

	protected $id;
	protected $partner_login;
	protected $prefix;
	protected $partner_frame;
	/**
	 * @return mixed
	 */
	public function getPrefix() {
		return $this->prefix;
	}
	


	
}
