<?php

namespace yii2module\account\domain\v2\entities;

use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii\web\IdentityInterface;
use yii2module\account\domain\v2\helpers\LoginHelper;

/**
 * Class LoginEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property integer $id
 * @property string $login
 * @property string $email
 * @property string $token
 * @property array $roles
 * @property string $avatar
 * @property string $username
 * @property string $created_at
 * @property SecurityEntity $security
 */
class LoginEntity extends BaseEntity implements IdentityInterface {

	protected $id;
	protected $login;
	protected $email;
	protected $status;
	protected $roles;
	protected $security;
	
	public function rules() {
		return [
			[['login', 'status'], 'trim'],
			[['login', 'status'], 'required'],
			[['status'], 'integer'],
		];
	}
	
	public function getAvatar() {
		$avatar = env('url.frontend') . '/images/avatars/_default.jpg';
		return $avatar;
	}
	
	public function getCreatedAt() {
		return $this->creation_date;
	}
	
	public function getUsername() {
		return LoginHelper::format($this->login);
	}

	public function getIinFixed() {
		if(empty($this->iin_fixed)) {
			return  false;
		}
		return $this->iin_fixed;
	}
	
	public function setRoles($value) {
		if(!empty($value)) {
			$this->roles = ArrayHelper::toArray($value);
		}
	}
	
	public function fieldType() {
		$fieldTypeConfig = [
			'id' => 'integer',
			'parent_id' => 'integer',
			'subject_type' => 'integer',
		];
		return $fieldTypeConfig;
	}
	
	public static function findIdentity($id) {}

	public static function findIdentityByAccessToken($token, $type = null) {}

	public function getId() {
		return intval($this->id);
	}
	
	public function getToken() {
		return $this->getAuthKey();
	}
	
	public function getAuthKey() {
		if(!$this->security instanceof SecurityEntity) {
			return null;
		}
		return $this->security->token;
	}

	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}

	public function fields()
	{
		$fields = parent::fields();
		unset($fields['security']);
		return $fields;
	}
}
