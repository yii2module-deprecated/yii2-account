<?php

namespace tests\functional\v1\services;

use Codeception\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use UnitTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

/**
 * Class AuthTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class AuthTest extends Unit
{
	
	public function testAuthentication()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->auth->authentication(LoginEnum::LOGIN_ADMIN, LoginEnum::PASSWORD);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
		$this->tester->assertEntityFormat(LoginEnum::getEntityFormat(), $entity);
		$this->tester->assertEquals(LoginEnum::TOKEN_ADMIN, $entity->token);
	}
	
	public function testAuthenticationBadPassword()
	{
		try {
			Yii::$domain->account->auth->authentication(LoginEnum::LOGIN_ADMIN, LoginEnum::PASSWORD_INCORRECT);
			$this->tester->assertTrue(false);
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}
	
	public function testAuthenticationNotFoundUser()
	{
		try {
			Yii::$domain->account->auth->authentication(LoginEnum::LOGIN_NOT_EXISTS, LoginEnum::PASSWORD);
			$this->tester->assertTrue(false);
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}
	
	public function testAuthenticationByToken()
	{
		/** @var LoginEntity $entity */
		$entity = Yii::$domain->account->auth->authenticationByToken(LoginEnum::TOKEN_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
		$array = $entity->toArray();
		$this->tester->assertTrue(empty($array['token']));
		$this->tester->assertEntityFormat(LoginEnum::getEntityFormat(), $entity);
	}
	
	public function testAuthenticationByBadToken()
	{
		try {
			/** @var LoginEntity $entity */
			Yii::$domain->account->auth->authenticationByToken(LoginEnum::TOKEN_NOT_INCORRECT);
			$this->tester->assertTrue(false);
		} catch(UnauthorizedHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testDenyAccess()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			Yii::$domain->account->auth->denyAccess();
			$this->tester->assertTrue(false);
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertTrue(true);
		}
	}
	
	public function testDenyAccessForGuest()
	{
		try {
			Yii::$domain->account->auth->denyAccess();
			$this->tester->assertTrue(false);
		} catch(InvalidConfigException $e) {
			// for console
			$this->tester->assertEquals('Unable to determine the request URI.', $e->getMessage());
		}
	}
	
}
