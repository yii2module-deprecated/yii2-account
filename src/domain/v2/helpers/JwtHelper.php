<?php

namespace yii2module\account\domain\v2\helpers;

use Firebase\JWT\JWT;
use yii2lab\domain\Alias;
use yii2lab\helpers\yii\ArrayHelper;
use yii2module\account\domain\v2\entities\JwtEntity;

/**
 * Class JwtEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property $id integer
 * @property $issuer string iss: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, генерирующим токен
 * @property $subject string sub: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, о которой содержится информация в данном токене. Значения с этим ключом должны быть уникальны в контексте стороны, генерирующей JWT.
 * @property $audience string[] aud: массив чувствительных к регистру строк или URI, являющийся списком получателей данного токена. Когда принимающая сторона получает JWT с данным ключом, она должна проверить наличие себя в получателях — иначе проигнорировать токен
 * @property $expiration integer exp: время в формате Unix Time, определяющее момент, когда токен станет не валидным
 * @property $not_before integer nbf: в противоположность ключу exp, это время в формате Unix Time, определяющее момент, когда токен станет валидным
 */

class JwtHelper {

	private static $key = 'example_key';
	
	public static function encode(JwtEntity $jwtEntity) {
		$data = self::entityToToken($jwtEntity);
		//prr($data,1,1);
		$token = JWT::encode($data, self::$key);
		return $token;
		
	}
	
	public static function decode($jwt) {
		$decoded = JWT::decode($jwt, self::$key, array('HS256', 'SHA512', 'HS384'));
		$decoded = ArrayHelper::toArray($decoded);
		$alias = self::getAliasInstance();
		$data = $alias->decode($decoded);
		$jwtEntity = new JwtEntity($data);
		return $jwtEntity;
	}
	
	private static function entityToToken(JwtEntity $jwtEntity) {
		$data = $jwtEntity->toArray();
		$data = array_filter($data, function ($value) {return $value !== null;});
		$alias = self::getAliasInstance();
		$data = $alias->encode($data);
		return $data;
	}
	
	private static function getAliasInstance() {
		$alias = new Alias();
		$alias->setAliases(self::getAliases());
		return $alias;
	}
	
	private static function getAliases() {
		return [
			'issuer' => 'iss',
			'subject' => 'sub',
			'audience' => 'aud',
			'expiration' => 'exp',
			'not_before' => 'nbf',
		];
	}
	
}
