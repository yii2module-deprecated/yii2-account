<?php

namespace yii2module\account\domain\v2\models;

use yii\db\ActiveRecord;
use paulzi\jsonBehavior\JsonBehavior;

/**
 * Class UserConfirm
 *
 * @package yii2module\account\domain\v2\models
 *
 * @property $login
 * @property $action
 * @property $code
 * @property $data
 * @property $expire
 * @property $created_at
 */
class UserConfirm extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user_confirm}}';
	}
	
	public static function primaryKey()
	{
		return ['login'];
	}
	
	public function behaviors()
	{
		return [
			'rulesJson' => [
				'class' => JsonBehavior::class,
				'attributes' => ['data'],
			],
		];
	}
	
}
