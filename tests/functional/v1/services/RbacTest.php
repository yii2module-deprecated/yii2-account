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
			Yii::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testCanByAdmin()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		try {
			Yii::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}
	
	public function testCanByGuest()
	{
		try {
			Yii::$domain->rbac->manager->can('oBackendAll');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testCanByGuest2()
	{
		try {
			Yii::$domain->rbac->manager->can('@');
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	/*public function testCanByGuest3()
	{
		try {
			Yii::$domain->rbac->manager->can('?');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}
	
	public function testCanByUser2()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			Yii::$domain->rbac->manager->can('?');
			$this->tester->assertTrue(true);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(false);
		}
	}*/
}
