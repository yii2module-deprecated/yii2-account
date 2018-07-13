<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii2lab\extension\registry\helpers\Registry;
use yii2lab\misc\enums\HttpHeaderEnum;
use yii2module\account\domain\v2\entities\LoginEntity;

class AuthHelper {

	const KEY = 'authToken';
	
	public static function setToken($token) {
		if (Yii::$app->user->enableSession) {
			Yii::$app->session['token'] = $token;
		}
		Registry::set(self::KEY, $token);
	}
	
	public static function getToken() {
		if(Yii::$app->user->enableSession && !empty(Yii::$app->session['token'])) {
			return Yii::$app->session['token'];
		}
		$token = Registry::get(self::KEY, false);
		if($token) {
			return $token;
		}
		if(!Yii::$app->user->getIsGuest() && Yii::$app->user->identity instanceof LoginEntity && Yii::$app->user->identity->getAuthKey()) {
			return Yii::$app->user->identity->getAuthKey();
		}
		$token = Yii::$app->request->headers->get(HttpHeaderEnum::AUTHORIZATION);
		if(!empty($token)) {
			return $token;
		}
		$token = Yii::$app->request->get(HttpHeaderEnum::AUTHORIZATION);
		if(!empty($token)) {
			return $token;
		}
		return null;
	}
	
}
