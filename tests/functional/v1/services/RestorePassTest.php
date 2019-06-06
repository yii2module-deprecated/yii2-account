<?php

namespace tests\functional\v1\services;

use Exception;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\test\Test\Unit;

class RestorePassTest extends Unit {
	
	const login = '77783177384';
	
	const PACKAGE = 'yii2module/yii2-account';
	
	public function testRequest() {
		$entity = \App::$domain->account->restorePassword->request(RestorePassTest::login);
		$this->tester->assertEquals($entity, null);
	}
	
	public function testConfirm() {
		try {
			\App::$domain->account->restorePassword->confirm(RestorePassTest::login, '');
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testCheckCode() {
		try {
			\App::$domain->account->restorePassword->checkActivationCode(RestorePassTest::login, '111111', 'Wwwqqq111');
			$this->tester->assertBad();
		} catch(Exception $e) {
			$this->tester->assertNice();
		}
	}
}
