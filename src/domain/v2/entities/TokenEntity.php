<?php

namespace yii2module\account\domain\v2\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class TokenEntity
 * 
 * @package yii2module\account\domain\v2\entities
 * 
 * @property $user_id
 * @property $token
 * @property $ip
 * @property $platform
 * @property $browser
 * @property $version
 * @property $created_at
 */
class TokenEntity extends BaseEntity {

	protected $user_id;
	protected $token;
	protected $ip;
	protected $platform;
	protected $browser;
	protected $version;
	protected $created_at;

}
