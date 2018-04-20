<?php

namespace yii2module\account\domain\v2\models;

use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property string $password write-only password
 */
class User extends ActiveRecord 
{

	public $updated_at;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user}}';
	}
	
	public static function primaryKey()
	{
		return ['id'];
	}
	
}
