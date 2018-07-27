<?php

namespace tests\functional\v1\services;

use yii2lab\test\Test\Unit;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2module\account\domain\v2\entities\LoginEntity;
use tests\functional\v1\enums\LoginEnum;

class LoginTest extends Unit
{
	
	public function testOneByLogin()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->login->oneByLogin(LoginEnum::LOGIN_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOne()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->login->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOneWithRelation()
	{
		$query = Query::forge();
		//$query->with('assignments');
		$query->with('security');
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->login->oneById(LoginEnum::ID_ADMIN, $query);
		
		$oo = [
			'id' => '381949',
			'login' => '77771111111',
			'status' => '1',
			'roles' => [
				'rAdministrator',
			],
			/*'assignments' => [
				'rAdministrator',
			],*/
			//'token' => LoginEnum::TOKEN_ADMIN,
			'email' => '',
			'created_at' => '2018-03-28 21:00:13',
		];
		
		$this->tester->assertEntity($oo, $entity);
	}
	
	public function testOneByLoginNotFound()
	{
		try {
			Yii::$domain->account->login->oneByLogin(LoginEnum::LOGIN_NOT_EXISTS);
			$this->tester->assertTrue(false);
		} catch(NotFoundHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testOneByIdNotFound()
	{
		try {
			Yii::$domain->account->login->oneById(LoginEnum::ID_NOT_EXISTS);
			$this->tester->assertTrue(false);
		} catch(NotFoundHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testAll()
	{
		/** @var BaseEntity[] $collection */
		$query = Query::forge();
		$collection = Yii::$domain->account->login->all($query);
		$this->tester->assertCollection([
			0 => LoginEnum::getUser(LoginEnum::ID_ADMIN),
			2 => LoginEnum::getUser(LoginEnum::ID_USER),
		], $collection);
	}
	
}
