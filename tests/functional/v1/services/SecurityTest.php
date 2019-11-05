<?php

namespace  tests\functional\v1\services;

use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\fixtures\UserFixture;
use yii2lab\test\fixtures\UserSecurityFixture;
use yii2lab\test\Test\Unit;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\tests\functional\v1\enums\LoginEnum;

class SecurityTest extends Unit
{
	public function _before()
	{
		$this->tester->haveFixtures([
			[
				'class' => UserFixture::class,
				'dataFile' => '@vendor/yii2module/yii2-account/src/domain/v2/fixtures/data/user.php'
			],
			[
				'class' => UserAssignmentFixture::class,
				'dataFile' => '@vendor/yii2module/yii2-account/src/domain/v2/fixtures/data/user_assignment.php'
			],
			[
				'class' => UserSecurityFixture::class,
				'dataFile' => '@vendor/yii2module/yii2-account/src/domain/v2/fixtures/data/user_security.php'
			],
		]);
	}

	public function testOneById()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->security->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity([
			'id' => LoginEnum::ID_ADMIN,
			'email' => '',
			//'token' => LoginEnum::TOKEN_ADMIN,
			'password_hash' => LoginEnum::PASSWORD_HASH,
		], $entity);
	}
	
	public function testSecurityCheck()
	{
		$collection = \App::$domain->account->security->all();
		$this->tester->assertEquals([], $collection);
	}
	
}
