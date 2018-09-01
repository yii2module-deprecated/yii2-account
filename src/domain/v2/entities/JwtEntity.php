<?php

namespace yii2module\account\domain\v2\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class JwtEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property $id integer
 * @property $issuer_url string iss: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, генерирующим токен
 * @property $subject_id integer ID пользователя
 * @property $subject_url string sub: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, о которой содержится информация в данном токене. Значения с этим ключом должны быть уникальны в контексте стороны, генерирующей JWT.
 * @property $audience string[] aud: массив чувствительных к регистру строк или URI, являющийся списком получателей данного токена. Когда принимающая сторона получает JWT с данным ключом, она должна проверить наличие себя в получателях — иначе проигнорировать токен
 * @property $expiration integer exp: время в формате Unix Time, определяющее момент, когда токен станет не валидным
 * @property $not_before integer nbf: в противоположность ключу exp, это время в формате Unix Time, определяющее момент, когда токен станет валидным
 */
class JwtEntity extends BaseEntity {
	
	protected $id;
	protected $issuer_url;
	protected $subject_id;
	protected $subject_url;
	protected $audience;
	protected $expiration;
	protected $not_before;

}
