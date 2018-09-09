<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii2lab\extension\registry\helpers\Registry;
use yii2lab\extension\web\enums\HttpHeaderEnum;
use yii2module\account\domain\v2\entities\LoginEntity;

class AuthHelper {

	const KEY = 'authToken';
	
	public static function setToken($token) {
		Registry::set(self::KEY, $token);
		self::setTokenToSession($token);
	}
	
	public static function getToken() {
		$token = Registry::get(self::KEY);
		if($token) {
			return $token;
		}
		$token = self::getTokenFromSession();
		if($token) {
			return $token;
		}
		$token = self::getTokenFromQuery();
		if($token) {
			return $token;
		}
		return null;
		//return self::getTokenFromIdentity();
	}
	
	public static function updateTokenViaSession() {
		if(Yii::$app->user->enableSession) {
			AuthHelper::setToken(AuthHelper::getTokenFromSession());
		}
		return null;
	}
	
	public static function getTokenFromSession() {
		if(Yii::$app->user->enableSession) {
			return Yii::$app->session->get(self::KEY);
		}
		return null;
	}
	
	public static function setTokenToSession($token) {
		if(Yii::$app->user->enableSession) {
			$tokenFromSession = self::getTokenFromSession();
			if ($tokenFromSession !== $token) {
				Yii::$app->session->set(self::KEY, $token);
			}
		}
	}
	
	public static function getTokenFromQuery() {
		$token = Yii::$app->request->headers->get(HttpHeaderEnum::AUTHORIZATION);
		if(!empty($token)) {
			return $token;
		}
		$token = Yii::$app->request->getQueryParam(strtolower(HttpHeaderEnum::AUTHORIZATION));
		if(!empty($token)) {
			return $token;
		}
		$token = Yii::$app->request->getQueryParam(HttpHeaderEnum::AUTHORIZATION);
		if(!empty($token)) {
			return $token;
		}
		return null;
	}
	
	public static function getTokenFromIdentity() {
		if(Yii::$app->user->getIsGuest()) {
			return null;
		}
		if(!Yii::$app->user->identity instanceof LoginEntity) {
			return null;
		}
		$token = Yii::$app->user->identity->getAuthKey();
		if($token) {
			return $token;
		}
		return null;
	}
	
}
