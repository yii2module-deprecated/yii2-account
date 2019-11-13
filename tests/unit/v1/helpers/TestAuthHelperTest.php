<?php

namespace tests\unit\v1\helpers;

use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\fixtures\UserFixture;
use yii2lab\test\fixtures\UserSecurityFixture;
use yii2lab\test\Test\Unit;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\TestAuthHelper;
use yii2module\account\tests\functional\v1\enums\LoginEnum;

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
