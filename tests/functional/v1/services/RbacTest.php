<?php

namespace tests\functional\v1\services;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use tests\functional\v1\enums\LoginEnum;
use yii\web\ForbiddenHttpException;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

/**
 * Class LoginTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class RbacTest extends Unit
{
	
	public function testCanByUser()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			Yii::$domain->account->rbac->can('oBackendAll');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testCanByAdmin()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		try {
			Yii::$domain->account->rbac->can('oBackendAll');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}
	
	public function testCanByGuest()
	{
		//TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		try {
			Yii::$domain->account->rbac->can('oBackendAll');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
}
