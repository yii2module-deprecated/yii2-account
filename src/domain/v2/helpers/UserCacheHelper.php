<?php

namespace yii2module\account\domain\v2\helpers;

use yii2lab\extension\registry\interfaces\RegistryInterface;
use yii2mod\helpers\ArrayHelper;

class UserCacheHelper implements RegistryInterface {
	
	static function get($key = null, $default = null) {
		$data = self::loadUserData();
		if(empty($data)) {
			return $default;
		}
		return ArrayHelper::getValue($data, $key, $default);
	}
	
	static function has($key) {
		$data = self::loadUserData();
		if(empty($data)) {
			return false;
		}
		return ArrayHelper::has($data, $key);
	}
	
	static function set($key, $value) {
		$data = self::loadUserData();
		ArrayHelper::set($data, $key, $value);
		self::saveUserData($data);
	}
	
	static function remove($key) {
		$data = self::loadUserData();
		ArrayHelper::remove($data, $key);
		self::saveUserData($data);
	}
	
	static function reset() {
		self::saveUserData([]);
	}
	
	static function load($data) {
		// TODO: Implement load() method.
	}
	
	private static function loadUserData() {
		$cacheKey = static::getKey();
		$data = \Yii::$app->cache->get($cacheKey);
		if(empty($data)) {
			return [];
		}
		return $data;
	}
	
	private static function saveUserData($data) {
		$cacheKey = static::getKey();
		\Yii::$app->cache->set($cacheKey, $data);
	}
	
	private static function getKey() {
		$identity = \App::$domain->account->auth->identity;
		return static::class . BL . $identity->id;
	}
	
}
