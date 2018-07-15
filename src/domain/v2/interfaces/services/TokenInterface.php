<?php

namespace yii2module\account\domain\v2\interfaces\services;

/**
 * Interface TokenInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\TokenInterface $repository
 */
interface TokenInterface {
	
	public function forge($userId, $ip, $expire = null);
	public function validate($token, $ip);
	public function deleteAllExpired();
	public function deleteOneByToken($token);
	
}
