<?php

namespace tests\unit\services;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2module\account\domain\entities\LoginEntity;
use tests\functional\enums\LoginEnum;

/**
 * Class LoginTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class LoginTest extends Unit
{
	
	public function testOneByLogin()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$app->account->login->oneByLogin(LoginEnum::LOGIN_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOne()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$app->account->login->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOneByLoginNotFound()
	{
		try {
			Yii::$app->account->login->oneByLogin(LoginEnum::LOGIN_NOT_EXISTS);
			$this->tester->assertTrue(false);
		} catch(NotFoundHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testOneByIdNotFound()
	{
		try {
			Yii::$app->account->login->oneById(LoginEnum::ID_NOT_EXISTS);
			$this->tester->assertTrue(false);
		} catch(NotFoundHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testAll()
	{
		/** @var BaseEntity[] $collection */
		$query = Query::forge();
		$collection = Yii::$app->account->login->all($query);
		$this->tester->assertCollection([
			0 => LoginEnum::getUser(LoginEnum::ID_ADMIN),
			2 => LoginEnum::getUser(LoginEnum::ID_USER),
		], $collection);
	}
	
}
