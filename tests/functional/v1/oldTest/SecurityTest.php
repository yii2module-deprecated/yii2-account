<?php

namespace  tests\functional\v1\oldTest;

use yii2lab\test\fixtures\UserAssignmentFixture;
use yii2lab\test\fixtures\UserFixture;
use yii2lab\test\fixtures\UserSecurityFixture;
use yii2lab\test\Test\Unit;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\tests\functional\v1\enums\LoginEnum;

class SecurityTest extends Unit
{


	public function testSecurityCheck()
	{
		$collection = \App::$domain->account->security->all();
		$this->tester->assertEquals([], $collection);
	}
	
}
