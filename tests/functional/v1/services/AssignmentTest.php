<?php

namespace tests\functional\v1\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\rbac\domain\entities\AssignmentEntity;
use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\Test\Unit;
use yii2module\account\tests\functional\v1\enums\LoginEnum;

class AssignmentTest extends Unit
{


	public function testAll()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();

		$query->where('user_id', LoginEnum::ID_ADMIN);
		$collection = \App::$domain->rbac->assignment->all($query);
		$this->tester->assertEquals([
			[
				'user_id' => LoginEnum::ID_ADMIN,
				'item_name' => 'rAdministrator',
			],
		], ArrayHelper::toArray($collection));

		$this->tester->assertCount(1, $collection);
	}

	public function testAll2()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', LoginEnum::ID_USER_2);
		$collection = \App::$domain->rbac->assignment->all($query);
		$this->tester->assertEquals([
			[
				'user_id' => LoginEnum::ID_USER_2,
				'item_name' => 'rUnknownUser',
			],
			[
				'user_id' => LoginEnum::ID_USER_2,
				'item_name' => 'rResmiUnknownUser',
			],
		], ArrayHelper::toArray($collection), true);
		$this->tester->assertCount(2, $collection);
	}

	public function testAllAssignments()
	{
		/** @var AssignmentEntity[] $collection */
		$collection = \App::$domain->rbac->assignment->getAssignments(LoginEnum::ID_USER_2);
		$this->tester->assertEquals([
			'rUnknownUser' => [
				'userId' => LoginEnum::ID_USER_2,
				'roleName' => 'rUnknownUser',
				'createdAt' => '1486774821',
			],
			'rResmiUnknownUser' => [
				'userId' => LoginEnum::ID_USER_2,
				'roleName' => 'rResmiUnknownUser',
				'createdAt' => '1486774821',
			],
		], ArrayHelper::toArray($collection));
		$this->tester->assertCount(2, $collection);
	}

	public function testIsHasRole()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rUnknownUser');
		$this->tester->assertTrue($isHas);

		$isHas = \App::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rResmiUnknownUser');
		$this->tester->assertTrue($isHas);
	}

	public function testIsHasRoleNegative()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rAdministrator');
		$this->tester->assertFalse($isHas);
	}

	public function testAllUserIdsByRole()
	{
		$ids = \App::$domain->rbac->assignment->getUserIdsByRole('rUnknownUser');
		$this->tester->assertEquals([
			381069,
			381070,
			375664,
			381073,
			381074,
			381075,
		], $ids);

		$ids = \App::$domain->rbac->assignment->getUserIdsByRole('rAdministrator');
		$this->tester->assertEquals([
			0 => 381949,
			1 => 381076,
		], $ids);
	}

}
