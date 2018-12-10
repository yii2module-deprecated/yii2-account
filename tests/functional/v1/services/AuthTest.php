<?php

namespace tests\functional\v1\services;

use yii2lab\test\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class AuthTest extends Unit
{
	
	public function testAuthentication()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->auth->authentication(LoginEnum::LOGIN_ADMIN, LoginEnum::PASSWORD);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
		$this->tester->assertEntityFormat(LoginEnum::getEntityFormat(), $entity);
		//$this->tester->assertEquals(LoginEnum::TOKEN_ADMIN, $entity->token);
	}
	
	public function testAuthenticationBadPassword()
	{
		try {
			\App::$domain->account->auth->authentication(LoginEnum::LOGIN_ADMIN, LoginEnum::PASSWORD_INCORRECT);
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}
	
	public function testAuthenticationNotFoundUser()
	{
		try {
			\App::$domain->account->auth->authentication(LoginEnum::LOGIN_NOT_EXISTS, LoginEnum::PASSWORD);
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}
	
	public function testAuthenticationByToken()
	{
		/** @var LoginEntity $loginEntity */
		$loginEntity = \App::$domain->account->auth->authentication(LoginEnum::LOGIN_ADMIN, LoginEnum::PASSWORD);
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->auth->authenticationByToken($loginEntity->token);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
		$array = $entity->toArray();
		$this->tester->assertTrue(empty($array['token']));
		$this->tester->assertEntityFormat(LoginEnum::getEntityFormat(), $entity);
	}
	
	public function testAuthenticationByBadToken()
	{
		try {
			/** @var LoginEntity $entity */
			\App::$domain->account->auth->authenticationByToken(LoginEnum::TOKEN_NOT_INCORRECT);
			$this->tester->assertBad();
		} catch(UnauthorizedHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDenyAccess()
	{
		TestAuthHelper::authById(LoginEnum::ID_USER);
		try {
			\App::$domain->account->auth->denyAccess();
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDenyAccessForGuest()
	{
		\App::$domain->account->auth->denyAccess();
		// for console
	}
	
	public function testLoginRequired()
	{
		\App::$domain->account->auth->loginRequired();
		// for console
	}
	
}
