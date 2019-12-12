<?php

namespace  tests\functional\v1\services;

use Exception;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\test\Test\Unit;

class RestorePassTest extends Unit {

	const login = '77477362300';

	public function testRequest() {
		try {
			$this->tester->assertEquals(\App::$domain->account->restorePassword->request(RestorePassTest::login), ['type'=> 'email']);
			$this->tester->assertNice();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertBad();
		}
	}

	public function testConfirm() {
		try {
			\App::$domain->account->restorePassword->confirm('77051086936', '111111');
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertNice();
		}
	}

	public function testCheckCode() {
		try {
			\App::$domain->account->restorePassword->checkActivationCode('77051086936', '111111', 'Wwwqqq111');
			$this->tester->assertBad();
		} catch(Exception $e) {
			$this->tester->assertNice();
		}
	}
}
