<?php

namespace yii2module\account\domain\v2\filters\login;

use yii\web\NotFoundHttpException;
use yii2module\account\domain\v2\interfaces\LoginValidatorInterface;

class LoginPhoneValidator implements LoginValidatorInterface {
	
	public $allowCountriesId;
	
	public function normalize($value) : string {
		try {
			$phoneInfoEntity = \App::$domain->geo->phone->parse($value);
			return $phoneInfoEntity->id;
		} catch(NotFoundHttpException $e) {
			return null;
		}
	}
	
	public function isValid($value) : bool {
		try {
			$phoneEntity = \App::$domain->geo->phone->oneByPhone($value);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(isset($this->allowCountriesId)) {
			if(in_array($phoneEntity->country_id, $this->allowCountriesId)) {
				return false;
			}
		}
		return true;
	}
	
}
