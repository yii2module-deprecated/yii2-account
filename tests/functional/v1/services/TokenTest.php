<?php

namespace tests\functional\v1\services;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use yii\web\NotFoundHttpException;
use yii2module\account\domain\v2\exceptions\InvalidIpAddressException;

/**
 * Class TokenTest
 *
 * @package tests\unit\services
 *
 *  @property UnitTester $tester
 */
class TokenTest extends Unit
{
	
	const IP = '192.168.44.92';
	const USER_ID = 111;
	const INVALID_TOKEN = 'invalid_token';
	const INVALID_IP = '111.111.111.111';
	
	protected function _before() {
		parent::_before();
		Yii::$domain->account->token->deleteAll();
	}
	
	public function testDoubleForge() {
		$token1 = Yii::$domain->account->token->forge(self::USER_ID, self::IP);
		$token2 = Yii::$domain->account->token->forge(self::USER_ID, self::IP);
		$this->tester->assertEquals($token1, $token2);
		
		$token3 = Yii::$domain->account->token->forge(self::USER_ID, self::INVALID_IP);
		$this->tester->assertNotEquals($token1, $token3);
		
		$token4 = Yii::$domain->account->token->forge(222, self::IP);
		$this->tester->assertNotEquals($token1, $token4);
	}
	
	public function testExpire() {
		$token = Yii::$domain->account->token->forge(self::USER_ID, self::IP, 1);
		
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		//sleep(2);
		//$this->tester->assertFalse($this->isValidateToken(self::USER_ID, $token, self::IP));
	}
	
	public function testException()
	{
		$token = Yii::$domain->account->token->forge(self::USER_ID, self::IP);
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		
		try {
			Yii::$domain->account->token->validate($token, self::INVALID_IP);
			$this->tester->assertTrue(false);
		} catch(InvalidIpAddressException $e) {
			$this->tester->assertTrue(true);
		}
		
		try {
			Yii::$domain->account->token->validate(self::INVALID_TOKEN, self::IP);
			$this->tester->assertTrue(false);
		} catch(NotFoundHttpException $e) {
			$this->tester->assertTrue(true);
		}
		
	}
	
	public function testDeleteOneByToken()
	{
		$token = Yii::$domain->account->token->forge(self::USER_ID, self::IP);
		Yii::$domain->account->token->deleteOneByToken($token);
		$this->tester->assertFalse($this->isValidateToken(self::USER_ID, $token, self::IP));
	}
	
	private function isValidateToken($userId, $token, $ip) {
		$this->tester->assertNotEmpty($token);
		$this->tester->assertNotEmpty($ip);
		try {
			$tokenEntity = Yii::$domain->account->token->validate($token, $ip);
			$this->tester->assertEquals($tokenEntity->user_id, $userId);
			return true;
		} catch(InvalidIpAddressException $e) {
		
		} catch(NotFoundHttpException $e) {
		
		}
		return false;
	}
}
