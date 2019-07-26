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
	protected function _before() {
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
