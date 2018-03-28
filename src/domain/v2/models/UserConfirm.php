<?php

namespace yii2module\account\domain\v2\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use paulzi\jsonBehavior\JsonBehavior;

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
			'timestamp' => [
				'class' => TimestampBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
				],
				'value' => function() { return date('Y-m-d H:i:s'); },
			],
			'rulesJson' => [
				'class' => JsonBehavior::class,
				'attributes' => ['data'],
			],
		];
	}
	
}
