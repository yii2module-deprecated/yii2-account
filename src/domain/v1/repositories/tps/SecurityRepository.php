<?php

namespace yii2module\account\domain\v1\repositories\tps;

use yii2lab\domain\repositories\TpsRepository;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v1\interfaces\repositories\SecurityInterface;
use yii2woop\generated\exception\tps\BadCredentialsException;
use yii2woop\generated\exception\tps\NotAuthenticatedException;
use yii2woop\generated\exception\tps\PasswordChangeException;
use yii2woop\generated\exception\tps\TooWeakPasswordException;
use yii2woop\generated\transport\TpsCommands;

class SecurityRepository extends TpsRepository implements SecurityInterface {
	
	public function changePassword($password, $newPassword) {
		
		$request = TpsCommands::passwordChangeByPreviousPassword($password, $newPassword);
		
		try {
			$request->send();
	
		} catch (NotAuthenticatedException $e) {
			throw new UnauthorizedHttpException();
		} catch (TooWeakPasswordException $e) {
			$error = new ErrorCollection();
			$error->add('new_password', 'account/auth', 'weak_password');
			throw new UnprocessableEntityHttpException($error);
		} catch (PasswordChangeException $e) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	public function changeEmail($password, $email) {
		$request = TpsCommands::updateEmail($password, $email);
		try {
			$request->send();
		} catch (NotAuthenticatedException $e) {
			throw new UnauthorizedHttpException();
		} catch (BadCredentialsException $e) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}