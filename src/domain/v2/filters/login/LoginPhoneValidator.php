<?php

namespace yii2module\account\domain\v2\filters\login;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\interfaces\LoginValidatorInterface;
use yii2module\account\domain\v2\services\RegistrationService;

class LoginPhoneValidator implements LoginValidatorInterface {

	public $allowCountriesId;

	public function normalize($value) : string {
		try {
			$phoneInfoEntity = \App::$domain->geo->phone->parse($value);
			return RegistrationService::checkPrefix().$phoneInfoEntity->id;
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection;
			$error->add('login', Yii::t('account/login', 'not_valid'));
			throw new UnprocessableEntityHttpException($error);
		}
	}

	public function isValid($value) : bool {
		return LoginHelper::validate($value);
	}
}
