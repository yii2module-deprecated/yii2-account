<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii2lab\extension\registry\helpers\Registry;
use yii2module\account\domain\v2\entities\LoginEntity;

class AuthHelper {

	public static function setToken($token) {
		if (Yii::$app->user->enableSession) {
			Yii::$app->session['token'] = $token;
		}
		Registry::set('authToken', $token);
	}
	
	public static function getToken() {
		if(Yii::$app->user->enableSession && !empty(Yii::$app->session['token'])) {
			return Yii::$app->session['token'];
		}
		$token = Registry::get('authToken', false);
		if($token) {
			return $token;
		}
		if(!Yii::$app->user->getIsGuest() && Yii::$app->user->identity instanceof LoginEntity && Yii::$app->user->identity->getAuthKey()) {
			return Yii::$app->user->identity->getAuthKey();
		}
		$token = Yii::$app->request->headers->get('Authorization');
		if(!empty($token)) {
			return $token;
		}
		return null;
	}
	
}
