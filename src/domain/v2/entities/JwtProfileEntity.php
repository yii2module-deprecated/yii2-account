<?php

namespace yii2module\account\domain\v2\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class JwtProfileEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property $name string
 * @property $key string
 * @property $life_time integer
 * @property $allowed_algs string[]
 * @property $default_alg string
 * @property $audience string[]
 */
class JwtProfileEntity extends BaseEntity {

    protected $name;
	protected $key;
    protected $life_time;
	protected $allowed_algs = [];
	protected $default_alg;
    protected $audience = [];

	public function rules() {
	    return [
	        [['key', 'allowed_algs', 'default_alg'], 'required'],
        ];
    }

}
