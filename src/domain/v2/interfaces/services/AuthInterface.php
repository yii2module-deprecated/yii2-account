<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\BaseEntity;

/**
 * Interface AuthInterface
 *
 * @package yii2module\account\domain\v2\interfaces\services
 *
 * @property \yii2module\account\domain\v2\interfaces\repositories\AuthInterface $repository
 * @property \yii2module\account\domain\v2\entities\LoginEntity $identity
 */
interface AuthInterface {

	public function authentication($login, $password);
	public function authenticationFromWeb($login, $password, $rememberMe = false);
	public function authenticationByToken($token, $type = null);
	public function getIdentity();
	public function logout();
	public function denyAccess();
	public function breakSession();
	public function loginRequired();
	public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id');
	
}