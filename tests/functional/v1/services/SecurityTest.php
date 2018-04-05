<?php

namespace tests\functional\v1\services;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;
use tests\functional\v1\enums\LoginEnum;

/**
 * Class LoginTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class SecurityTest extends Unit
{
	
	public function testOneById()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->security->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity([
			'id' => LoginEnum::ID_ADMIN,
			'email' => '',
			'token' => LoginEnum::TOKEN_ADMIN,
			'password_hash' => LoginEnum::PASSWORD_HASH,
		], $entity);
	}
	
	public function testSecurityCheck()
	{
		$collection = Yii::$domain->account->security->all();
		$this->tester->assertEquals([], $collection);
	}
	
}
