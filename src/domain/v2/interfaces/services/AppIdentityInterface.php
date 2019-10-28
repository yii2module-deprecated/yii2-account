<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

/**
 * Interface AppIdentityInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\AppIdentityInterface $repository
 */
interface AppIdentityInterface extends CrudInterface {

	public function checkKey($login, $key);
}
