<?php

namespace tests\unit\v1\helpers;

use yii2lab\test\Test\Unit;
use yii2module\account\domain\v2\helpers\LoginHelper;

class LoginHelperTest extends Unit
{
	
	public function testPregMatchLoginInternationalFormat()
	{
		expect(LoginHelper::pregMatchLogin('77758889900'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+7 (775) (888)-(99)-(00)'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+7 (775) (888)-(9900)'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+77758889900'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+7-775-888-99-00'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+7 775 888 99 00'))->equals('77758889900');
	}
	
	public function testPregMatchLoginKzFormat()
	{
		expect(LoginHelper::pregMatchLogin('87758889900'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('8 (775) (888)-(99)-(00)'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('8 (775) (888)-(9900)'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('87758889900'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('8-775-888-99-00'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('8 775 888 99 00'))->equals('77758889900');
	}
	
	public function testPregMatchLoginWithPrefix()
	{
		expect(LoginHelper::pregMatchLogin('R87758889900'))->equals('R77758889900');
		expect(LoginHelper::pregMatchLogin('R8 (775) (888)-(99)-(00)'))->equals('R77758889900');
		expect(LoginHelper::pregMatchLogin('R+7 (775) (888)-(99)-(00)'))->equals('R77758889900');
		expect(LoginHelper::pregMatchLogin('BS8 775 888 99 00'))->equals('BS77758889900');
	}

	public function testPregMatchLoginNonStandardFormat()
	{
		expect(LoginHelper::pregMatchLogin('+7   775  888  99  00'))->equals('77758889900');
		expect(LoginHelper::pregMatchLogin('+++++7------775----888----99-----00'))->equals('77758889900');
	}
	
	public function testSplitLogin()
	{
		expect(LoginHelper::splitLogin('R77758889900'))->equals([
			'prefix' => 'R',
			'phone' => '77758889900',
		]);
		expect(LoginHelper::splitLogin('R+77758889900'))->equals([
			'prefix' => 'R',
			'phone' => '+77758889900',
		]);
		expect(LoginHelper::splitLogin('77758889900'))->equals([
			'prefix' => '',
			'phone' => '77758889900',
		]);
		expect(LoginHelper::splitLogin('R8 (775) (888)-(99)-(00)'))->equals([
			'prefix' => 'R',
			'phone' => '8 (775) (888)-(99)-(00)',
		]);
		expect(LoginHelper::splitLogin('BS77758889900'))->equals([
			'prefix' => 'BS',
			'phone' => '77758889900',
		]);
	}
	
	public function testValidateFormatSuccess()
	{
		expect(LoginHelper::validate('+7   775  888  99  00'))->equals(true);
		expect(LoginHelper::validate('R8 (775) (888)-(99)-(00)'))->equals(true);
		expect(LoginHelper::validate('R+77758889900'))->equals(true);
		expect(LoginHelper::validate('77758889900'))->equals(true);
		expect(LoginHelper::validate('+++++7------775----888----99-----00'))->equals(true);
		expect(LoginHelper::validate('+7 (775) (888)-(99)-(00)'))->equals(true);
	}
	
	public function testValidatePrefixSuccess()
	{
		expect(LoginHelper::validate('R77758889900'))->equals(true);
		expect(LoginHelper::validate('B77758889900'))->equals(true);
		expect(LoginHelper::validate('BS77758889900'))->equals(true);
	}
	
	public function testValidateFormatFail()
	{
		expect(LoginHelper::validate('+7 775'))->equals(false);
		expect(LoginHelper::validate('R8 (775) (888)-(99)'))->equals(false);
		expect(LoginHelper::validate('R+7775888'))->equals(false);
		expect(LoginHelper::validate('777588899008'))->equals(false);
		expect(LoginHelper::validate('7775888990'))->equals(false);
		expect(LoginHelper::validate('(775) (888)-(99)-(00)'))->equals(false);
	}
	
	public function testValidatePrefixFail()
	{
		expect(LoginHelper::validate('G77758889900'))->equals(false);
		expect(LoginHelper::validate('BR77758889900'))->equals(false);
		expect(LoginHelper::validate('SR77758889900'))->equals(false);
		expect(LoginHelper::validate('RB77758889900'))->equals(false);
	}
	
	public function testParse()
	{
		expect(LoginHelper::parse('BS77758889900'))->equals([
			'prefix' => 'BS',
			'phone' => '77758889900',
		]);
		expect(LoginHelper::parse('R8 (775) (888)-(99)-(00)'))->equals([
			'prefix' => 'R',
			'phone' => '77758889900',
		]);
		expect(LoginHelper::parse('8 (775) (888)-(99)-(00)'))->equals([
			'prefix' => '',
			'phone' => '77758889900',
		]);
	}
	
}
