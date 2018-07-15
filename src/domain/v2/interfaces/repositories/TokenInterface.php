<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v2\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\repositories
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
interface TokenInterface extends CrudInterface {
	
	/**
	 * @param $token
	 *
	 * @return TokenEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneByToken($token);
	
	/**
	 * @param $ip
	 *
	 * @return TokenEntity[]
	 */
	public function allByIp($ip);
	public function allByUserId($userId);
	//public function deleteByIp($ip);
	public function deleteOneByToken($token);
	//public function deleteAllExpiredByIp($ip);
	public function deleteAllExpired();
	
}
