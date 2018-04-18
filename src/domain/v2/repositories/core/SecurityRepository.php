<?php

namespace yii2module\account\domain\v2\repositories\core;

use yii\web\NotFoundHttpException;
use yii2lab\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseActiveCoreRepository implements SecurityInterface {
	
	public $point = 'security';
	
	public function changePassword($password, $newPassword) {
		$response = $this->put('password', [
			'password' => $password,
			'new_password' => $newPassword,
		]);
	}
	
	public function changeEmail($password, $email) {
		$response = $this->put('email', [
			'password' => $password,
			'email' => $email,
		]);
	}
	
	/**
	 * @param string $token
	 * @param string $type
	 *
	 * @return SecurityEntity
	 */
	public function oneByToken($token, $type = null) {
		// TODO: Implement oneByToken() method.
	}
	
	/**
	 * @param integer $userId
	 * @param string  $password
	 *
	 * @return SecurityEntity|false
	 * @throws UnprocessableEntityHttpException
	 * @throws NotFoundHttpException
	 */
	public function validatePassword($userId, $password) {
		// TODO: Implement validatePassword() method.
	}
	
	/**
	 * @return string
	 */
	public function generateUniqueToken() {
		// TODO: Implement generateUniqueToken() method.
	}
}