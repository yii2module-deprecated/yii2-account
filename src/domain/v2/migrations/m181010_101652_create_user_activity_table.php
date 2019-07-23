<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m181010_101652_create_user_activity_table
 * 
 * @package 
 */
class m181010_101652_create_user_activity_table extends Migration {

	public $table = '{{%user_activity}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull(),
			'user_id' => $this->integer(),
			'domain' => $this->string(32),
			'service' => $this->string(32),
			'method' => $this->string(32),
			'request' => $this->text(),
			'response' => $this->text(),
			'platform' => $this->string(32),
			'browser' => $this->string(32),
			'version' => $this->string(40),
			'action' => $this->string(40),
			'created_at' => $this->timestamp()->defaultValue('now()')->notNull(),
		];
	}

	public function afterCreate()
	{
		
	}

}