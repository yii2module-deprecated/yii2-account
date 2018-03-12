<?php

namespace yii2module\account\domain\v2\repositories\ar;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\repositories\ActiveArRepository;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends ActiveArRepository implements SecurityInterface {
	
	public function tableName() {
		return 'user_security';
	}
	
	public function fieldAlias() {
		return [
			'token' => 'auth_key',
		];
	}
	
	/**
	 * @param $userId
	 * @param $password
	 *
	 * @return SecurityEntity|false
	 * @throws UnprocessableEntityHttpException
	 */
	public function validatePassword($userId, $password) {
		$securityEntity = $this->isValidPassword($userId, $password);
		if(!$securityEntity) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
		return $securityEntity;
	}
	
	/**
	 * @param $userId
	 * @param $password
	 *
	 * @return SecurityEntity|false
	 */
	public function isValidPassword($userId, $password) {
		$securityEntity = $this->oneById($userId);
		if(Yii::$app->security->validatePassword($password, $securityEntity->password_hash)) {
			return $securityEntity;
		}
		return false;
	}
	
	public function changePassword($password, $newPassword) {
		$userId = Yii::$app->user->id;
		$securityEntity = $this->validatePassword($userId, $password);
		$securityEntity->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
		$this->update($securityEntity);
	}
	
	public function changeEmail($password, $email) {
		$userId = Yii::$app->user->id;
		$securityEntity = $this->validatePassword($userId, $password);
		$securityEntity->email = $email;
		$this->update($securityEntity);
	}

}