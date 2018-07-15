<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m180715_094956_create_user_token_table
 * 
 * @package 
 */
class m180715_094956_create_user_token_table extends Migration {

	public $table = '{{%user_token}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'user_id' => $this->integer(11)->notNull(),
			'token' => $this->string(255)->notNull(),
			'ip' => $this->string(40)->notNull(),
			'platform' => $this->string(32)->notNull(),
			'browser' => $this->string(32)->notNull(),
			'version' => $this->string(10)->notNull(),
			'created_at' => $this->integer()->notNull(),
			'expire_at' => $this->integer()->notNull(),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'user_id',
			'{{%user}}',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myCreateIndexUnique('token');
	}

}