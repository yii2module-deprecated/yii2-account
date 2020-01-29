<?php

namespace  tests\functional\v1\services;

use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\fixtures\UserFixture;
use yii2lab\test\fixtures\UserSecurityFixture;
use yii2lab\test\fixtures\UserTokenFixture;
use yii2lab\test\Test\Unit;
use Yii;
use yii\web\NotFoundHttpException;
use yii2module\account\domain\v2\exceptions\InvalidIpAddressException;
use yii2module\account\domain\v2\exceptions\NotFoundLoginException;

class ActivityServiceTest extends Unit
{
	
	const IP = '192.168.44.92';
	const USER_ID = 381069;
	const USER_ID_2 = 381949;
	const INVALID_TOKEN = 'invalid_token';
	const INVALID_IP = '111.111.111.111';
	

	public function testForgeNotFoundLogin() {
		$body = [
			'domain' => 'test',
			'service' => 'serviceTest',
			'method' => 'methodTest',
			'request' => 'requestTest',
			'response' => 'responseTest',
		];
		try {
			$activityEntity = \App::$domain->account->activity->create($body);
			dd($activityEntity);
		} catch(NotFoundLoginException $e) {
			$this->tester->assertTrue(false);
		}
//		\App::$domain->account->activity->oneById()
		$this->tester->assertTrue(true);
	}

}
