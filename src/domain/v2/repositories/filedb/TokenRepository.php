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
	
	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function insert(BaseEntity $entity) {
		// TODO: Implement insert() method.
	}
	
	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function update(BaseEntity $entity) {
		// TODO: Implement update() method.
	}
	
	/**
	 * @param BaseEntity $entity
	 *
	 */
	public function delete(BaseEntity $entity) {
		// TODO: Implement delete() method.
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity[]|null
	 */
	public function all(Query $query = null) {
		// TODO: Implement all() method.
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return integer
	 */
	public function count(Query $query = null) {
		// TODO: Implement count() method.
	}
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return \yii2lab\domain\BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null) {
		// TODO: Implement oneById() method.
	}
}
