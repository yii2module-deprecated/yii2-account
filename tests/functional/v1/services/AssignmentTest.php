<?php

namespace tests\functional\v1\services;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use yii2lab\domain\data\Query;
use tests\functional\v1\enums\LoginEnum;
use yii2module\account\domain\v2\entities\AssignmentEntity;

/**
 * Class LoginTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class AssignmentTest extends Unit
{
	
	public function testAll()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', LoginEnum::ID_ADMIN);
		$collection = Yii::$domain->rbac->assignment->all($query);
		$this->tester->assertCollection([
			[
				'user_id' => LoginEnum::ID_ADMIN,
				'item_name' => 'rAdministrator',
			],
		], $collection);
		$this->tester->assertCount(1, $collection);
	}
	
	public function testAll2()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', LoginEnum::ID_USER_2);
		$collection = Yii::$domain->rbac->assignment->all($query);
		$this->tester->assertCollection([
			[
				'user_id' => LoginEnum::ID_USER_2,
				'item_name' => 'rUnknownUser',
			],
			[
				'user_id' => LoginEnum::ID_USER_2,
				'item_name' => 'rResmiUnknownUser',
			],
		], $collection);
		$this->tester->assertCount(2, $collection);
	}
	
	public function testAllAssignments()
	{
		/** @var AssignmentEntity[] $collection */
		$collection = Yii::$domain->rbac->assignment->getAssignments(LoginEnum::ID_USER_2);
		$this->tester->assertEquals([
			'rUnknownUser' => new yii\rbac\Assignment([
				'userId' => LoginEnum::ID_USER_2,
				'roleName' => 'rUnknownUser',
				'createdAt' => '1486774821',
			]),
			'rResmiUnknownUser' => new yii\rbac\Assignment([
				'userId' => LoginEnum::ID_USER_2,
				'roleName' => 'rResmiUnknownUser',
				'createdAt' => '1486774821',
			]),
		], $collection);
		$this->tester->assertCount(2, $collection);
	}
	
	public function testIsHasRole()
	{
		$isHas = Yii::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rUnknownUser');
		$this->tester->assertTrue($isHas);
		
		$isHas = Yii::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rResmiUnknownUser');
		$this->tester->assertTrue($isHas);
	}
	
	public function testIsHasRoleNegative()
	{
		$isHas = Yii::$domain->rbac->assignment->isHasRole(LoginEnum::ID_USER_2, 'rAdministrator');
		$this->tester->assertFalse($isHas);
	}
	
	public function testAllUserIdsByRole()
	{
		$ids = Yii::$domain->rbac->assignment->getUserIdsByRole('rUnknownUser');
		$this->tester->assertEquals([
			381069,
			381070,
			375664,
			381073,
			381074,
			381075,
		], $ids);
		
		$ids = Yii::$domain->rbac->assignment->getUserIdsByRole('rAdministrator');
		$this->tester->assertEquals([
			0 => 381949,
			1 => 381076,
		], $ids);
	}
	
}
