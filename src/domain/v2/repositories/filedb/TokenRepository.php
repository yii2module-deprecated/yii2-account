<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2module\account\domain\v2\entities\TokenEntity;
use yii2module\account\domain\v2\interfaces\repositories\TokenInterface;

/**
 * Class TokenRepository
 * 
 * @package yii2module\account\domain\v2\repositories\ar
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {
	
	
	/**
	 * @param $token
	 *
	 * @return TokenEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneByToken($token) {
		// TODO: Implement oneByToken() method.
	}
	
	/**
	 * @param $ip
	 *
	 * @return TokenEntity[]
	 */
	public function allByIp($ip) {
		// TODO: Implement allByIp() method.
	}
	
	public function allByUserId($userId) {
		// TODO: Implement allByUserId() method.
	}
	
	public function deleteOneByToken($token) {
		// TODO: Implement deleteOneByToken() method.
	}
	
	public function deleteAllExpired() {
		// TODO: Implement deleteAllExpired() method.
	}
}
