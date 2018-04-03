<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\interfaces\services\LoginInterface;
use yii2module\account\domain\forms\LoginForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii2woop\generated\enums\SubjectType;
use yii2lab\extension\registry\helpers\Registry;
/**
 * Class LoginService
 *
 * @package yii2module\account\domain\services
 *
 * @property \yii2module\account\domain\interfaces\repositories\LoginInterface $repository
 */
class AuthPseudoService extends ActiveBaseService {
	
	
	/**
	 * @param string      $login
	 * @param string      $ip
	 * @param SubjectType $subjectType
	 * @param string|null $email
	 *
	 * @return LoginEntity
	 * @throws UnprocessableEntityHttpException
	 */
	
	public function pseudoAuthentication($login, $ip, $email = null, $parentLogin = null) {
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->repository->pseudoAuthentication($login, $ip, SubjectType::USER_UNIDENT_PSEUDO, $email, $parentLogin);
		if(empty($loginEntity)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		Registry::set('authToken', $loginEntity->token);
		//$loginEntity->showToken();
		return $loginEntity;
	}
	
}
