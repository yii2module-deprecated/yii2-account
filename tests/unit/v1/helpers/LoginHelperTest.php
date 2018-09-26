<?php

namespace tests\unit\v1\helpers;

use yii2lab\test\Test\Unit;
use yii2module\account\domain\v2\helpers\LoginHelper;

class LoginHelperTest extends Unit
{
	
	public function testPregMatchLoginInternationalFormat()
	{
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('77758889900'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+7 (775) (888)-(99)-(00)'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+7 (775) (888)-(9900)'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+77758889900'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+7-775-888-99-00'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+7 775 888 99 00'), '77758889900');
	}
	
	public function testPregMatchLoginKzFormat()
	{
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('87758889900'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('8 (775) (888)-(99)-(00)'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('8 (775) (888)-(9900)'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('87758889900'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('8-775-888-99-00'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('8 775 888 99 00'), '77758889900');
	}
	
	public function testPregMatchLoginWithPrefix()
	{
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('R87758889900'), 'R77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('R8 (775) (888)-(99)-(00)'), 'R77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('R+7 (775) (888)-(99)-(00)'), 'R77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('BS8 775 888 99 00'), 'BS77758889900');
	}

	public function testPregMatchLoginNonStandardFormat()
	{
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+7   775  888  99  00'), '77758889900');
		$this->tester->assertEquals(LoginHelper::pregMatchLogin('+++++7------775----888----99-----00'), '77758889900');
	}
	
	public function testSplitLogin()
	{
		$this->tester->assertEquals(LoginHelper::splitLogin('R77758889900'), [
			'prefix' => 'R',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
		$this->tester->assertEquals(LoginHelper::splitLogin('R+77758889900'), [
			'prefix' => 'R',
            'country_code' => '+7',
			'phone' => '7758889900',
		]);
		$this->tester->assertEquals(LoginHelper::splitLogin('77758889900'), [
			'prefix' => '',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
//		$this->tester->assertEquals(LoginHelper::splitLogin('R8 (775) (888)-(99)-(00)'), [
//			'prefix' => 'R',
//            'country_code' => '8',
//			'phone' => '8 (775) (888)-(99)-(00)',
//		]);
		$this->tester->assertEquals(LoginHelper::splitLogin('BS77758889900'), [
			'prefix' => 'BS',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
	}
	
	public function testValidateFormatSuccess()
	{
		$this->tester->assertEquals(LoginHelper::validate('+7   775  888  99  00'), true);
		$this->tester->assertEquals(LoginHelper::validate('R8 (775) (888)-(99)-(00)'), true);
		$this->tester->assertEquals(LoginHelper::validate('R+77758889900'), true);
		$this->tester->assertEquals(LoginHelper::validate('77758889900'), true);
		$this->tester->assertEquals(LoginHelper::validate('+++++7------775----888----99-----00'), true);
		$this->tester->assertEquals(LoginHelper::validate('+7 (775) (888)-(99)-(00)'), true);
	}
	
	public function testValidatePrefixSuccess()
	{
		$this->tester->assertEquals(LoginHelper::validate('R77758889900'), true);
		$this->tester->assertEquals(LoginHelper::validate('B77758889900'), true);
		$this->tester->assertEquals(LoginHelper::validate('BS77758889900'), true);
	}
	
	public function testValidateFormatFail()
	{
		$this->tester->assertEquals(LoginHelper::validate('+7 775'), false);
		$this->tester->assertEquals(LoginHelper::validate('R8 (775) (888)-(99)'), false);
		$this->tester->assertEquals(LoginHelper::validate('R+7775888'), false);
		$this->tester->assertEquals(LoginHelper::validate('777588899008'), false);
		$this->tester->assertEquals(LoginHelper::validate('7775888990'), false);
		$this->tester->assertEquals(LoginHelper::validate('(775) (888)-(99)-(00)'), false);
	}
	
	public function testValidatePrefixFail()
	{
		$this->tester->assertEquals(LoginHelper::validate('G77758889900'), false);
		$this->tester->assertEquals(LoginHelper::validate('BR77758889900'), false);
		$this->tester->assertEquals(LoginHelper::validate('SR77758889900'), false);
		$this->tester->assertEquals(LoginHelper::validate('RB77758889900'), false);
	}
	
	public function testParse()
	{
		$this->tester->assertEquals(LoginHelper::parse('BS77758889900'), [
			'prefix' => 'BS',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
		$this->tester->assertEquals(LoginHelper::parse('R8 (775) (888)-(99)-(00)'), [
			'prefix' => 'R',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
		$this->tester->assertEquals(LoginHelper::parse('8 (775) (888)-(99)-(00)'), [
			'prefix' => '',
            'country_code' => '7',
			'phone' => '7758889900',
		]);
	}
	
}
