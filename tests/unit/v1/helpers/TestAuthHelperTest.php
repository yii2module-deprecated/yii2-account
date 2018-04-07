<?php

namespace tests\unit\v1\helpers;

use Codeception\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use Yii;
use yii2lab\test\base\_support\UnitTester;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

/**
 * Class TestAuthHelperTest
 *
 * @package tests\unit\v1\helpers
 *
 * @property UnitTester $tester
 */
class TestAuthHelperTest extends Unit
{
	
	public function testAuthById()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $loginEntity);
	}
	
	public function testAuthByLogin()
	{
		TestAuthHelper::authByLogin(LoginEnum::LOGIN_ADMIN);
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $loginEntity);
	}
	
}
